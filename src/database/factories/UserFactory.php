<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'age' => $this->faker->numberBetween(18, 60),
            'address' => $this->faker->address,
            'points' => $this->faker->numberBetween(10, 60),
            'photo_url' => $this->faker->imageUrl()
        ];
    }
}
