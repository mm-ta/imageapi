<?php

namespace App\Http\Controllers\V1;

use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\ResizeImageRequest;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use App\Repositories\Interfaces\ImageManipulationRepositoryInterface;

class ImageManipulationController extends Controller
{
    protected ImageManipulationRepositoryInterface $imageManipulationRepository;
    protected AlbumRepositoryInterface $albumRepository;

    public function __construct(ImageManipulationRepositoryInterface $imageManipulationRepository,
                                AlbumRepositoryInterface $albumRepository)
    {
        $this->imageManipulationRepository = $imageManipulationRepository;
        $this->albumRepository = $albumRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->imageManipulationRepository->allPaginated($request->user()->id, 15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImageManipulationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function resize(ResizeImageRequest $request)
    {
        $all = $request->all();

        /** @var \Illuminate\Http\UploadedFile|string $image */
        $image = $all['image'];

        unset($all['image']);

        $data = [
            'type' => $this->imageManipulationRepository->resizeType(),
            'data' => json_encode($all),
            'user_id' => $request->user()->id,
            'w' => $all['w'],
            'ext' => $all['ext'] ?? null,
            'album_id' => null
        ];

        if ($this->albumRepository->getAlbumById($all['album_id'])->user_id != $request->user()->id) {
            abort(403, 'You are not allowed to edit this album');
        }

        $data['album_id'] = $all['album_id'];

        /**
         * make directory and storing image
         *  */
        $directory = 'images/' . Str::random() . '/';
        $absolutePath = public_path($directory);
        File::makeDirectory($absolutePath);

        // File
        if ($image instanceof UploadedFile) {
            $data['name'] = $image->getClientOriginalName();

            $filname = pathinfo($data['name'], PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $originalPath = $absolutePath . $data['name'];

            // upload into directory
            $image->move($absolutePath, $data['name']);
        } else { // Url
            $data['name'] = pathinfo($image, PATHINFO_BASENAME);
            $filname = pathinfo($image, PATHINFO_FILENAME);
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $originalPath = $absolutePath . $data['name'];

            // store in directory
            copy($image, $originalPath);
        }
        $data['path'] = $directory . $data['name'];


        // retrieve resizing aspect ratios
        $width = $data['w'];
        $height = $data['h'] ?? false;
        list($InterventionImage, $width, $height) = $this->getImageAspects($width, $height, $originalPath);


        // store as an extension desired by the client
        if ($data['ext'] !== null ) {
            $extension = $data['ext']; // change extension to the
        }

        // rename image.jpg => image-resized.jpg
        $resizeFileName = $filname . '-resized.' . $extension;

        $InterventionImage->resize($width, $height)->save($absolutePath . $resizeFileName);

        // add relative path
        $data['output_path'] = $directory . $resizeFileName;

        // store in database (create a model)
        $imageManipulation = $this->imageManipulationRepository->create($data);

        return $this->imageManipulationRepository->present($imageManipulation);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @param  int  $imageManipulationId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $imageManipulationId)
    {
        $image = $this->imageManipulationRepository->getById($imageManipulationId);
        if ($request->user()->id != $image->user_id) {
            abort(403, 'You are not allowed to access this image.');
        }

        return $this->imageManipulationRepository->present($image);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param  int $imageId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $imageId)
    {
        $image = $this->imageManipulationRepository->getById($imageId);
        if ($request->user()->id != $image->user_id) {
            abort(403, 'You are not allowed to delete this image');
        }

        $this->imageManipulationRepository->delete($image);

        return response([
            'message' => 'Your Image manipulation deleted'],
             200);
    }

    /**
     * show resized images of the album
     *
     * @param \Illuminate\Http\Request  $request
     * @param int $albumId
     * @return \Illuminate\Http\Response
     */
    public function byAlbum(Request $request, int $albumId)
    {
        $album = $this->albumRepository->getAlbumById($albumId);
        if ($album->user_id != $request->user()->id) {
            abort(403, 'You are not allowed to access this album.');
        }

        return $this->imageManipulationRepository->allPaginatedByAlbum($albumId);
    }

    protected function getImageAspects($width, $height, $originalPath) : array
    {
        $image = Image::make($originalPath);
        $originalWidth = (float) $image->width();
        $originalHeight = (float) $image->height();

        // to resize by percentage
        if (str_ends_with($width, '%')) {
            $widthRatio = (float)((str_replace('%', '', $width)) / 100); // divide by one hundred to turn percent into ratio
            $heightRatio = $height ? (float)((str_replace('%', '', $height)) / 100) : $widthRatio;
            $newWidth = $originalWidth * $widthRatio;
            $newHeight = $originalHeight * $heightRatio;
        } else { // to resize by one fixed size in pixel
            $newWidth = (float)$width;
            $ratio = (float)($newWidth / $originalWidth);
            $newHeight = $height ? (float)$height : ($originalHeight * $ratio);
        }

        return [$image, $newWidth, $newHeight];
    }
}
