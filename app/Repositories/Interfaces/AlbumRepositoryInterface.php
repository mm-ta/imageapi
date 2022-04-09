<?php

namespace App\Repositories\Interfaces;

use App\Models\Album;

interface AlbumRepositoryInterface
{
    public function allPaginated($userId, $per_page = 15);
    public function getAlbumById($albumId);
    public function album(Album $album);
    public function createAlbum($details);
    public function updateAlbum($albumId, $details);
    public function delete(Album $album);
}
