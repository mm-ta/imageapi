<?php

namespace App\Repositories;

use App\Http\Resources\V1\ImageManipulationResource;
use App\Models\ImageManipulation;
use App\Repositories\Interfaces\ImageManipulationRepositoryInterface;

class ImageManipulationRepository implements ImageManipulationRepositoryInterface
{
    public function allPaginated($userId, $per_page = 15)
    {
        return ImageManipulationResource::collection(ImageManipulation::where('user_id', $userId)
            ->paginate($per_page));
    }

    public function allPaginatedByAlbum($albumId)
    {
        return ImageManipulationResource::collection(ImageManipulation::where([
            'album_id' => $albumId
        ])->paginate());
    }

    public function getById($imageManipulationById)
    {
        return ImageManipulation::findOrFail($imageManipulationById);
    }

    public function present(ImageManipulation $image)
    {
        return new ImageManipulationResource($image);
    }

    public function create($details)
    {
        return ImageManipulation::create($details);
    }

    public function delete(ImageManipulation $image)
    {
        $image->delete();
    }

    public function resizeType()
    {
        return ImageManipulation::TYPE_RESIZE;
    }
}
