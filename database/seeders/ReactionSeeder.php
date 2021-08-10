<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reaction;
use DB;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date("Y-m-d H:i:s");
        DB::table('reactions')->insert([
            ['name' => 'like', 'icon_name' => 'like_icon', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'love', 'icon_name' => 'love_icon', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'grrr', 'icon_name' => 'grrr_icon', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'dislike', 'icon_name' => 'dislike_icon', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'grateful', 'icon_name' => 'grateful_icon', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
