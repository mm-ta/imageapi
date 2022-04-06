<?php

namespace App\Repositories;

use App\Http\Resources\V1\ImageManipulationResource;
use App\Models\ImageManipulation;
use App\Repositories\Interfaces\ImageManipulationRepositoryInterface;

class ImageManipulationRepository implements ImageManipulationRepositoryInterface
{
    public function allPaginated($per_page = 15)
    {
        return ImageManipulationResource::collection(ImageManipulation::paginate($per_page));
    }

    public function presentById($imageManipulationById)
    {
        return new ImageManipulationResource(ImageManipulation::find($imageManipulationById));
    }

    public function present(ImageManipulation $image)
    {
        return new ImageManipulationResource($image);
    }

    public function create($details)
    {
        return ImageManipulation::create($details);
    }

    public function delete($imageManipulationById)
    {
        ImageManipulation::find($imageManipulationById)->delete();
    }

    public function resizeType()
    {
        return ImageManipulation::TYPE_RESIZE;
    }
}
