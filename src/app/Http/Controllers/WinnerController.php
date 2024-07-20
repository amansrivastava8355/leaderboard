<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Winner;


class WinnerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/current-winner",
     *     summary="Get current winner in every 5 minutes",
     *     tags={"Winner"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="No winner declared yet"),
     * )
     */
    public function show()
    {
        $winner = Winner::with('user')->latest('won_at')->first();

        if ($winner) {
            return response()->json([
                'user' => [
                    'id' => $winner->user->id,
                    'name' => $winner->user->name,
                    'age' => $winner->user->age,
                    'address' => $winner->user->address,
                    'photo_url' => $winner->user->photo_url
                ],
                'points' => $winner->points,
                'won_at' => $winner->won_at
            ]);
        } else {
            return response()->json(['message' => 'No winner declared yet.'], 404);
        }
    }
}
