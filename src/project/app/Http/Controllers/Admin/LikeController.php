<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends Controller
{
    /**
     * Undocumented function
     *
     * @param LikeRequest $request
     * @return JsonResponse
     */
    public function like(LikeRequest $request): JsonResponse
    {
        try {
            $likeable = $request->likeable();

            $isLiked = $likeable->likes()->where('user_id', auth()->id())->exists();

            if (! $isLiked) {
                $likeable->likes()->create([
                    'user_id' => auth()->id(),
                    'vote' => $request->input('vote'),
                ]);
            }

            return response()->json(['status' => true,]);

        } catch (Exception $e) {
            return response()->json(['status' => false], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Undocumented function
     *
     * @param LikeRequest $request
     * @return JsonResponse
     */
    public function unlike(LikeRequest $request): JsonResponse
    {
        try {
            $likeable = $request->likeable();

            $like = $likeable->likes()->where('user_id', auth()->id())->first();

            if ($like) {
                $like->delete();
            }

            return response()->json(['status' => true]);
        } catch (Exception $e) {
            return response()->json(['status' => false]);
        }
    }
}
