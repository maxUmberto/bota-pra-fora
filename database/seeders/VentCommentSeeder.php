<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VentComment;

class VentCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VentComment::factory()->create();
    }
}
