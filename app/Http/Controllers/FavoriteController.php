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
    /**
     *  認証不要 function => (index, show, store, destroy)
     */
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
        ]);

        // Duplicate registration check
        $favorite = new Favorite();
        if ($favorite->existFavorite($request)) {
            throw new FavoriteBadRequest();
        }

        // already deleted, restore it
        $deleted_favorite = $favorite->deletedFavorite($request);

        if ($deleted_favorite) {
            $id = $deleted_favorite->value('id');
            $deleted_favorite->restore();

            return new FavoriteResource(Favorite::find($id));
        }

        //insert new record
        $favorite->fill($request->only([
            'track', 'album', 'artist', 'image_path', 'release_date'
        ]))->save();

        return new FavoriteResource($favorite);
    }

    public function destroy($favorite_id)
    {
        // Favorite exist check exception
        try {
            $favorite = Favorite::searchFavoriteById($favorite_id);
        } catch (ModelNotFoundException $e) {
            throw new FavoriteNotFoundException();
        }

        // delete record
        $favorite->delete();

        return response('Deleted successfully.', 200);
    }

    /**
     *  認証後 function => (userFavoritesIndex, userFavoriteCreate, userFavoriteDestroy)
     */
    public function userFavoritesIndex($user_id)
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

    public function userFavoriteCreate(Request $request)
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
        if ($favorite->existFavoriteUsers($request)) {
            throw new FavoriteBadRequest();
        }

        // already deleted, restore it
        $deleted_favorite = $favorite->deletedFavoriteUsers($request);

        if ($deleted_favorite) {
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

    public function userFavoriteDestroy($favorite_id, $user_id)
    {
        // Favorite exist check exception
        try {
            $favorite = Favorite::searchFavoriteUsersById($favorite_id, $user_id);
        } catch (ModelNotFoundException $e) {
            throw new FavoriteNotFoundException();
        }

        // delete record
        $favorite->delete();

        return response('Deleted successfully.', 200);
    }
}
