<?php

namespace App\Exceptions;

use Exception;

class FavoriteNotFoundException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'errors' => [
                'code' => 404,
                'title' => 'Favorite Not Found',
                'detail' => 'Unable to locate the favorite with the given information.',
            ]
        ], 404);
    }
}
