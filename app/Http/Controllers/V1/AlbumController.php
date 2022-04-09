<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->albumRepository->allPaginated($request->user()->id, 15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAlbumRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAlbumRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        $album = $this->albumRepository->createAlbum($data);

        return $this->albumRepository->album($album);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $album_id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $albumId)
    {
        $album = $this->albumRepository->getAlbumById($albumId);
        if ($album->user_id != $request->user()->id) {
            abort(403, 'Unauthorized.');
        }

        return $this->albumRepository->album($album);
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
        $album = $this->albumRepository->getAlbumById($albumId);
        if ($album->user_id != $request->user()->id) {
            abort(403, 'Unauthorized.');
        }

        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        return $this->albumRepository->updateAlbum($albumId, $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $albumId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $albumId)
    {
        $album = $this->albumRepository->getAlbumById($albumId);
        if ($album->user_id != $request->user()->id) {
            abort(403, 'Unauthorized.');
        }

        $this->albumRepository->delete($album);

        return response('resource deleted', 200);
    }
}
