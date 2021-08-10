<?php

namespace Database\Factories;

use App\Models\Vent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'        => User::factory(),
            'vent_content'   => $this->faker->paragraph(),
            'allow_comments' => $this->faker->boolean()
        ];
    }

    /**
     * Indicate that a vent allows comment.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function allowComments()
    {
        return $this->state(function (array $attributes) {
            return [
                'allow_comments' => true,
            ];
        });
    }
    
    /**
     * Indicate that a vent doesnt allow comment.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function dontAllowComments()
    {
        return $this->state(function (array $attributes) {
            return [
                'allow_comments' => false,
            ];
        });
    }
}
