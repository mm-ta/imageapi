<?php

namespace App\Repositories;

use App\Models\Album;
use App\Http\Resources\V1\AlbumResource;
use App\Repositories\Interfaces\AlbumRepositoryInterface;

class AlbumRepository implements AlbumRepositoryInterface
{
    public function allPaginated($userId, $per_page = 15)
    {
        return AlbumResource::collection(Album::where('user_id', $userId)
            ->paginate($per_page));
    }

    public function getAlbumById($albumId)
    {
        return Album::findOrFail($albumId);
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

    public function delete(Album $album)
    {
        $album->delete();
    }
}
