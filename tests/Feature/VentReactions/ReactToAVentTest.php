<?php

namespace Tests\Feature\VentReactions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

// Models
use App\Models\Reaction;
use App\Models\User;
use App\Models\Vent;

class ReactToAVentTest extends TestCase {

    use DatabaseMigrations;

    /**
     * Check if user is able to react to a vent
     *
     * @return void
     */
    public function testUserReactingToAVent() {
        $vent = Vent::factory()->create();
        $user = User::factory()->create();
        $reaction = Reaction::inRandomOrder()->first();

        $response = $this->actingAs($user)
                        ->postJson("/api/vent/{$vent->id}/react/{$reaction->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success'
                ]);

        $this->assertDatabaseHas('reaction_vent', [
            'reaction_id' => $reaction->id,
            'user_id'     => $user->id,
            'vent_id'     => $vent->id,
        ]);
    }

    /**
     * Check if the user is able to remove his reaction to a vent
     * 
     * @return void
     */
    public function testUserCanRemoveReactFromAVent() {
        $vent = Vent::factory()->create();
        $user = User::factory()->create();
        $reaction = Reaction::inRandomOrder()->first();

        $vent->reactions()->attach($reaction->id, ['user_id' => $user->id]);

        $response = $this->actingAs($user)
                        ->postJson("/api/vent/{$vent->id}/react/{$reaction->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success'
                ]);

        $this->assertDatabaseMissing('reaction_vent', [
            'reaction_id' => $reaction->id,
            'user_id'     => $user->id,
            'vent_id'     => $vent->id,
        ]);
    }
}
