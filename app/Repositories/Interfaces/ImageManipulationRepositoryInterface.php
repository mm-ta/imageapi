<?php

namespace App\Repositories\Interfaces;

use App\Models\ImageManipulation;

interface ImageManipulationRepositoryInterface
{
    public function allPaginated($per_page = 15);
    public function imageManipulationById($imageManipulationById);
    public function imageManipulation(ImageManipulation $image);
    public function createImageManipulation($details);
    public function deleteImageManipulation($imageManipulationById);
}
