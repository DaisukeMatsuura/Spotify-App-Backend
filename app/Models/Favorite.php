<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Favorite extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function deletedFavorite($request)
    {
        $deleted_favorite = Favorite::onlyTrashed()
                                ->where('track', $request->track)
                                ->where('artist', $request->artist);

        if ($deleted_favorite->exists()) {
            return $deleted_favorite;
        } else {
            return false;
        }
    }

    public function deletedFavoriteUsers($request)
    {
        $deleted_favorite = Favorite::onlyTrashed()
            ->where('track', $request->track)
            ->where('artist', $request->artist)
            ->where('user_id', $request->user_id);

        if ($deleted_favorite->exists()) {
            return $deleted_favorite;
        } else {
            return false;
        }
    }

    public function existFavorite($request)
    {
        return DB::table('favorites')
                    ->where('track', $request->track)
                    ->where('artist', $request->artist)
                    ->whereNull('user_id')
                    ->whereNull('deleted_at')
                    ->exists();
    }

    public function existFavoriteUsers($request)
    {
        return DB::table('favorites')
                    ->where('track', $request->track)
                    ->where('artist', $request->artist)
                    ->where('user_id', $request->user_id)
                    ->whereNull('deleted_at')
                    ->exists();
    }

    static function searchPublicFavorite()
    {
        return Favorite::whereNull('user_id')
                    ->whereNull('deleted_at');
    }

    static function searchFavoriteById($favorite_id)
    {
        return  Favorite::where('id', $favorite_id)
                        ->whereNull('user_id')
                        ->firstOrFail();
    }

    static function searchFavoriteUsersById($favorite_id, $user_id)
    {
        return  Favorite::where('id', $favorite_id)
                        ->where('user_id', $user_id)
                        ->firstOrFail();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
