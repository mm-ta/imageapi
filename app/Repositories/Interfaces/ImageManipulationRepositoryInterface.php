<?php

namespace App\Repositories\Interfaces;

use App\Models\ImageManipulation;

interface ImageManipulationRepositoryInterface
{
    public function allPaginated($per_page = 15);
    public function allPaginatedByAlbum($albumId);
    public function presentById($imageManipulationById);
    public function present(ImageManipulation $image);
    public function create($details);
    public function delete($imageManipulationById);
    public function resizeType();
}
