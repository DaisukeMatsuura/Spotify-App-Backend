<?php

namespace App\Exceptions;

use Exception;

class FavoriteBadRequest extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            'errors' => [
                'code' => 400,
                'title' => 'Bad Request',
                'detail' => 'You request is already been registered.',
            ]
        ], 400);
    }
}
