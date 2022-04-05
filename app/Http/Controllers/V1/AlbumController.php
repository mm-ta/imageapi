<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use App\Repositories\Interfaces\AlbumRepositoryInterface;

class AlbumController extends Controller
{
    protected AlbumRepositoryInterface $albumRepository;

    public function __construct(AlbumRepositoryInterface $albumRepository)
    {
        $this->albumRepository = $albumRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->albumRepository->allPaginated();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAlbumRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAlbumRequest $request)
    {
        $album = $this->albumRepository->createAlbum($request->all());

        return $this->albumRepository->album($album);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $album_id
     * @return \Illuminate\Http\Response
     */
    public function show(int $album_id)
    {
        return $this->albumRepository->albumById($album_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAlbumRequest  $request
     * @param  int $albumId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAlbumRequest $request, int $albumId)
    {
        return $this->albumRepository->updateAlbum($albumId, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $albumId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $albumId)
    {
        // $album->delete();
        return $this->albumRepository->deleteAlbum($albumId);

        return response('resource deleted', 204);
    }
}
