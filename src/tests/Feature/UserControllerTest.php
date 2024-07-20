<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_users()
    {
        $users = User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_it_can_create_user()
    {
        Storage::fake('s3');

        $response = $this->json('POST', '/api/users', [
            'name' => 'abhishek',
            'age' => '34',
            'address' => 'up',
            'photo' => UploadedFile::fake()->image('photo.png')
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'name',
                     'age',
                     'address',
                     'photo_url',
                     'updated_at',
                     'created_at',
                     '_id'
                 ]);

       
    }

    public function test_it_can_show_user()
    {
        $user = User::factory()->create();
        
        $response = $this->getJson('/api/users/'.$user->id);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     '_id' => $user->id
                 ]);
    }

    public function test_it_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson('/api/users/'.$user->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_it_can_update_user_points()
    {
        $user = User::factory()->create();
        $userPoints = $user->points;
        $newPoints = 10;
        $updatedPoints = $userPoints + $newPoints;

        $response = $this->patchJson('/api/users/'.$user->id.'/points', ['points' => $newPoints]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'points' => $updatedPoints
                 ]);

        $this->assertDatabaseHas('users', ['_id' => $user->id, 'points' => $updatedPoints]);
    }
}
