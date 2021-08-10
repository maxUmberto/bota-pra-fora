<?php

namespace Database\Factories;

use App\Models\VentView;
use App\Models\Vent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VentViewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VentView::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'           => User::factory(),
            'vent_id'           => Vent::factory(),
            'view_location_lat' => $this->faker->latitude(-90, 90),
            'view_location_lon' => $this->faker->longitude(-180, 180)
        ];
    }
}
