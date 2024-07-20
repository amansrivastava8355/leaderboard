<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LeaderboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/leaderboard",
     *     summary="Get leaderboard",
     *     @OA\Response(response=200, description="Successful operation"),
     *     tags={"Leaderboard Avg Score"},
     *     @OA\Response(response=400, description="Bad request"),
     * )
     */
    public function index()
    {
        $users = User::orderByDesc('points')->get();

        $grouped = $users->groupBy('points')->map(function ($group) {
            return [
                'names' => $group->pluck('name')->all(),
                'average_age' => round($group->avg('age'))
            ];
        });

        return response()->json($grouped);
    }
}
