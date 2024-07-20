<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Winner;
use Carbon\Carbon;

class WinnerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_current_winner()
    {
        // Create a user with maximum points
        $user = User::factory()->create([
            'name' => 'John Doe',
            'age' => 30,
            'address' => '123 Main St',
            'photo_url' => 'http://example.com/photo.jpg',
            'points' => 100
        ]);

        // Declare the winner
        $declaredAt = Carbon::now()->toIso8601String();
        $winner = Winner::create([
            'user_id' => $user->id,
            'points' => 100,
            'won_at' => $declaredAt
        ]);

        $response = $this->getJson('/api/current-winner');

        $response->assertStatus(200)
                 ->assertJson([
                     'user' => [
                         'id' => $user->id,
                         'name' => 'John Doe',
                         'age' => 30,
                         'address' => '123 Main St',
                         'photo_url' => 'http://example.com/photo.jpg'
                     ],
                     'points' => 100,
                     'won_at' => $declaredAt
                 ]);
    }

    public function test_it_returns_404_when_no_winner_declared()
    {
        $response = $this->getJson('/api/current-winner');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'No winner declared yet.'
                 ]);
    }
}

