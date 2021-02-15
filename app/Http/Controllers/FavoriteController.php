<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use App\Http\Resources\Favorite as FavoriteResource;
use App\Http\Resources\FavoriteCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\FavoriteNotFoundException;
use App\Exceptions\UserNotFoundException;

use App\Exceptions\FavoriteBadRequest;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class FavoriteController extends Controller
{
    public function index(Favorite $favorite)
    {
        // Favorite exist check exception
        try {
            $favorite->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new FavoriteNotFoundException();
        }

        return new FavoriteCollection($favorite->all());
    }

    public function userFavorites($user_id)
    {
        // User exist check exception
        try {
            $favorites = User::findOrFail($user_id)->favorites();
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }

        // Favorite exist check exception
        if (!$favorites->exists()) {
            throw new FavoriteNotFoundException();
        }

        return new FavoriteCollection($favorites->get());
    }

    public function show($favorite_id)
    {
        // Favorite exist check exception
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
            'user_id'      => 'required',
        ]);

        // Duplicate registration check
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
        $favorite->fill($request->only([
            'track', 'album', 'artist', 'image_path', 'release_date', 'user_id'
        ]))->save();

        return new FavoriteResource($favorite);
    }

    public function destroy($favorite_id)
    {
        // Favorite exist check exception
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
