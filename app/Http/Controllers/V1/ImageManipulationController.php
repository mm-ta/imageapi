<?php

namespace App\Http\Controllers\V1;

use App\Models\Album;
use Illuminate\Support\Str;
use App\Models\ImageManipulation;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\ResizeImageRequest;

class ImageManipulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        dd($image);
        unset($all['image']);

        $data = [
            'type' => ImageManipulation::TYPE_RESIZE,
            'data' => json_encode($all),
            'user_id' => null, // FIXME after implementing authentication.
        ];

        if (isset($all['album_id'])) {
            // TODO: implement

            $data['album_id'] = $all['album_id'];
        }

        /**
         * make directory and storing image
         *  */
        $directory = 'images/' . Str::random() . '/';
        $absolutePath = public_path($directory);
        File::makeDirectory($absolutePath);

        if ($image instanceof UploadedFile) {
            $data['name'] = $image->getClientOriginalName();

            $filname = pathinfo($data['name'], PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $originalPath = $absolutePath . $data['name'];

            // upload into directory
            $image->move($absolutePath, $data['name']);
        } else {
            $data['name'] = pathinfo($image, PATHINFO_BASENAME);
            $filname = pathinfo($image, PATHINFO_FILENAME);
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $originalPath = $absolutePath . $data['name'];

            // store in directory
            copy($image, $originalPath);
        }
        $data['path'] = $directory . $data['name'];

        /**
         * resizing
         */
        // image.jpg => image-resized.jpg
        $width = $data['w'];
        $height = $data['h'] ?? false;



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageManipulation  $imageManipulation
     * @return \Illuminate\Http\Response
     */
    public function show(ImageManipulation $imageManipulation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageManipulation  $imageManipulation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageManipulation $imageManipulation)
    {
        //
    }

    /**
     * show resized images of the album
     *
     * @param \App\Models\Album $album
     * @return \Illuminate\Http\Response
     */
    public function byAlbum(Album $album)
    {

    }

    protected function getImageAspects($width, $height = null, $originalPath) : array
    {
        $image = Image::make($originalPath);
        $originalWidth = $image->width();
        $originalHeight = $image->height();

        // to resize by percentage
        if (str_ends_with($width, '%')) {
            $widthRatio = (float)((str_replace('%', '', $width)) / 100);
            $heightRatio = $height ? (float)((str_replace('%', '', $height)) / 100) : $widthRatio;
            $newWidth = $originalWidth * $widthRatio;
            $newHeight = $originalHeight * $heightRatio;
        } else { // to resize by one fixed size in pixel
            $newWidth = (float)$width;
            $newRatio = (float)($newWidth / $originalWidth);
            $newHeight = $height ? (float)$height : ($originalHeight * $newRatio);
        }

        return [$image, $newWidth, $newHeight];
    }
}
