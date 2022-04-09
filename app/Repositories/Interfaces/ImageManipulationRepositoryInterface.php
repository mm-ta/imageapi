<?php

namespace App\Repositories\Interfaces;

use App\Models\ImageManipulation;

interface ImageManipulationRepositoryInterface
{
    public function allPaginated($userId, $per_page = 15);
    public function allPaginatedByAlbum($albumId);
    public function getById($imageManipulationById);
    public function present(ImageManipulation $image);
    public function create($details);
    public function delete(ImageManipulation $image);
    public function resizeType();
}
