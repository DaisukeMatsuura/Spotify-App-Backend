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
            return null;
        }
    }

    public function existFavorite($request)
    {
        return DB::table('favorites')
                    ->where('track', $request->track)
                    ->where('artist', $request->artist)
                    ->whereNull('deleted_at')
                    ->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
