<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LeaderboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_leaderboard()
    {
        $users = User::factory()->count(3)->create();

        $response = $this->getJson('/api/leaderboard');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }
}
