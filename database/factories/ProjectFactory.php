<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->lexify(),
            'date' => $this->faker->date(),
            'description' => $this->faker->paragraph(),
            'approved' => $this->faker->randomElement([true, false]),
            'title' => $this->faker->word(),
        ];
    }
}
