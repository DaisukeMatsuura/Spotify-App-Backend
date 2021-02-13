<?php

namespace App\Http\Controllers;

use App\Exceptions\FavoriteNotFoundException;
use App\Models\Favorite;
use App\Http\Resources\Favorite as FavoriteResource;
use App\Http\Resources\FavoriteCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Exceptions\FavoriteBadRequest;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Favorite $favorite)
    {
        // exist check exception
        try {
            Favorite::all();
        } catch (ModelNotFoundException $e) {
            throw new FavoriteNotFoundException();
        }

        return new FavoriteCollection($favorite->all());
    }

    public function show($favorite_id)
    {
        // exist check exception
        try {
           $favorite = Favorite::findOrFail($favorite_id);
        } catch (ModelNotFoundException $e) {
            throw new FavoriteNotFoundException();
        }

        return new FavoriteResource($favorite);
    }

    public function store(Request $request)
    {
        //validation
        $this->validate($request, [
            'track'        => 'required',
            'album'        => 'required',
            'artist'       => 'required',
            'image_path'   => 'required',
            'release_date' => 'required',
        ]);

        // exist check
        $favorite = new Favorite();
        if ($favorite->existFavorite($request)) {
            throw new FavoriteBadRequest();
        }

        // already deleted, restore it
        $deleted_favorite = $favorite->deletedFavorite($request);

        if (!is_null($deleted_favorite)) {
            $id = $deleted_favorite->value('id');
            $deleted_favorite->restore();

            return new FavoriteResource(Favorite::find($id));
        }

        //insert new record
        $favorite->fill($request->only(['track', 'album', 'artist', 'image_path', 'release_date']))->save();

        return new FavoriteResource($favorite);
    }

    public function destroy($favorite_id)
    {
        // exist check exception
        try {
            $favorite = Favorite::findOrFail($favorite_id);
        } catch (ModelNotFoundException $e) {
            throw new FavoriteNotFoundException();
        }

        // delete record
        $favorite->delete();

        return response('Deleted successfully.', 200);
    }
}
