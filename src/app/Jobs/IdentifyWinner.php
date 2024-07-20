<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Winner;
use Carbon\Carbon;

class IdentifyWinner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $maxPoints = User::max('points');
        $usersWithMaxPoints = User::where('points', $maxPoints)->get();

        if ($usersWithMaxPoints->count() === 1) {
            $winner = $usersWithMaxPoints->first();
            Winner::create([
                'user_id' => $winner->id,
                'points' => $winner->points,
                'won_at' => now()->toIso8601String()
            ]);
        }
    }
}
