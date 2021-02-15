<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class Favorite extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'type' => 'favorites',
                'favorite_id' => $this->id,
                'attributes' => [
                    'track'        => $this->track,
                    'album'        => $this->album,
                    'artist'       => $this->artist,
                    'image_path'   => $this->image_path,
                    'release_date' => $this->release_date,
                ],
                'relationships' => [
                    'user' => [
                        'data' => [
                            'user_id'   => $this->user ? $this->user->id : '',
                            'user_name' => $this->user ? $this->user->username : '',
                        ]
                    ]
                ],
            ],
            'links' => [
                'self' => url('/api/favorites/'.$this->id),
            ]
        ];
    }
}
