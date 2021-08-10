<?php

namespace Database\Factories;

use App\Models\VentComment;
use App\Models\User;
use App\Models\Vent;
use Illuminate\Database\Eloquent\Factories\Factory;

class VentCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VentComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'         => User::factory(),
            'vent_id'         => Vent::Factory()->allowComments(),
            'comment_content' => $this->faker->sentence()
        ];
    }
}
