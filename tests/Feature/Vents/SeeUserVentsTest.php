<?php

namespace Tests\Feature\Vents;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\User;

class SeeUserVentsTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test if a user can see all the vents he got
     *
     * @return void
     */
    public function test_get_all_user_vents(){
        $vent_qtt = rand(1,10);
        $user = User::factory()->hasVents($vent_qtt)->create();

        $response = $this->actingAs($user)
                        ->get('/api/vent/my-vents');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'vents' => [
                        '*' => [
                            'id',
                            'user_id',
                            'vent_content',
                            'allow_comments'
                        ]
                    ]
                ])
                ->assertJsonCount($vent_qtt, 'vents');
    }

    public function test_get_vents_for_a_user_without_vents() {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->get('/api/vent/my-vents');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'vents' => []
                ])
                ->assertJsonCount(0, 'vents');
    }
}
