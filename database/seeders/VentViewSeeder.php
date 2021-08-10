<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VentView;

class VentViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VentView::factory()->create();
    }
}
