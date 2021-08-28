<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

// Models
use App\Models\User;

class CreateVentTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test if an unlogged user cant create a vent
     *
     * @return void
     */
    public function testCreateVentWithoutBeenLoggedIn() {
        $user = User::factory()->make();

        $response = $this->post('/api/vent/new');

        $response->assertStatus(401);
    }

    /**
     * Test if a user cant create a vent without content
     *
     * @return void
     */
    public function testCreateVentWithoutContent() {        
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->postJson('/api/vent/new', [
                            'allow_comments' => array_rand([true, false], 1)
                        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'vent_content'
                    ]
                ]);
    }
    
    /**
     * Test if a user cant create a vent without the allow_comments
     * being true or false
     *
     * @return void
     */
    public function testCreateVentWithoutAllowComments() {        
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->postJson('/api/vent/new', [
                            'vent_content' => $this->faker()->paragraph()
                        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'allow_comments'
                    ]
                ]);
    }

    /**
     * Test if an user can create a vent and if the vent created is successfully
     * assigned to the user
     */
    public function testCreateVent() {

        $vent_content = $this->faker()->paragraph();
        $allow_comments = array_rand([true, false], 1);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->postJson('/api/vent/new', [
                            'vent_content'   => $vent_content,
                            'allow_comments' => $allow_comments
                        ]);
    
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'success'
                ])
                ->assertJsonFragment(['message' => 'Desabafo criado com sucesso'])
                ->assertJsonFragment(['success' => true]);
    
        $this->assertDatabaseCount('vents', 1);

        $user = User::with('vents')->find($user->id);
        $this->assertEquals(count($user->vents), 1);
        
        $vent = $user->vents->first();
        $this->assertEquals($vent->vent_content, $vent_content);
        $this->assertEquals($vent->allow_comments, $allow_comments);
    }
}
