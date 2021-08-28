<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

// Models
use App\Models\User;
use App\Models\Vent;
use App\Models\VentView;

class VentViewsTest extends TestCase {
    
    use DatabaseMigrations;

    /**
     * Test if the user can see a random vent and if the vent the user 
     * just saw is correctly saved at the database
     * 
     * @return void
     */
    public function testSeeRandomVent() {
        $vents = Vent::factory(5)->create();
        $user = User::factory()->create();

        $this->assertTheUserVisualizedTheVent($user);               
    }

    /**
     * Test if the user cant see the same vent twice
     * 
     * @return void
     */
    public function testUserCantSeeTheSameVentTwice() {
        $vents = Vent::factory(5)->create();
        $user = User::factory()->create();

        for($i = 0; $i < count($vents); $i++) {
            $this->assertTheUserVisualizedTheVent($user);
        }

        $user_vent_views = VentView::select('vent_id')->distinct()->count();
        $this->assertEquals($user_vent_views, count($vents));
    }

    /**
     * Test if after the user has visualized all the availables vents
     * none will be returned
     * 
     * @return void
     */
    public function testUserVisualizeAllAvailableVents() {
        $vents = Vent::factory()->create();
        $user = User::factory()->create();

        $this->assertTheUserVisualizedTheVent($user);
        $response = $this->actingAs($user)
                        ->get("api/vent/view");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'vent',
                    'message'
                ])
                ->assertJsonFragment(['vent' => null])
                ->assertJsonFragment(['success' => true]);
    }

    /**
     * Make the request to visualize a random Vent
     */
    private function assertTheUserVisualizedTheVent(User $user) {
        $response = $this->actingAs($user)
                        ->get("api/vent/view");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'vent' => [
                        'id',
                        'vent_content',
                        'allow_comments',
                    ]
                ])
                ->assertJsonMissing([
                    'user_id'
                ]);

        $response = json_decode($response->getContent());

        $this->assertDatabaseHas('vent_views', [
            'vent_id' => $response->vent->id,
            'user_id' => $user->id
        ]); 
    }
}