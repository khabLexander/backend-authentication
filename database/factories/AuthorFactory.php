<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->email(),
            'identification' => $this->faker->uuid(),
            'names' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'age' => $this->faker->numberBetween(18, 50),
        ];
    }
}
