<?php

namespace App\Repositories;

use App\Models\Album;
use App\Http\Resources\V1\AlbumResource;
use App\Repositories\Interfaces\AlbumRepositoryInterface;

class AlbumRepository implements AlbumRepositoryInterface
{
    public function allPaginated($per_page = 15)
    {
        return AlbumResource::collection(Album::paginate());
    }

    public function albumById($albumId)
    {
        return new AlbumResource(Album::find($albumId));
    }

    public function album(Album $album)
    {
        return new AlbumResource($album);
    }

    public function createAlbum($details)
    {
        return Album::create($details);
    }

    public function updateAlbum($albumId, $details)
    {
        $album = Album::find($albumId);
        $album->update($details);

        return new AlbumResource($album);
    }

    public function deleteAlbum($albumId)
    {
        Album::find($albumId)->delete();
    }
}
