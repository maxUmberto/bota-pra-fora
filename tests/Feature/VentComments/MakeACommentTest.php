<?php

namespace Tests\Feature\VentComments;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

// Models
use App\Models\User;
use App\Models\Vent;
use App\Models\VentComment;

class MakeACommentTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test if the user is able to create a comment
     *
     * @return void
     */
    public function testCanMakeAComment() {
        $vent = Vent::factory()->allowComments()->create();
        $user = User::factory()->create();
        $comment_content = $this->faker()->paragraph();

        $response = $this->actingAs($user)
                        ->postJson("/api/vent/{$vent->id}/comment", ["comment_content" => $comment_content]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success'
                ]);

        $this->assertDatabaseHas('vent_comments', [
            'comment_content' => $comment_content,
            'user_id'      => $user->id,
            'vent_id'      => $vent->id,
        ]);
    }

    /**
     * Test that if the user somehow tries to make a comment on a vent
     * that dont allow it, he wont be able to do so
     * 
     * @return void
     */
    public function testCantCommentOnAVentWithoutPermission() {
        $vent = Vent::factory()->dontAllowComments()->create();
        $user = User::factory()->create();
        $comment_content = $this->faker()->paragraph();

        $response = $this->actingAs($user)
                        ->postJson("/api/vent/{$vent->id}/comment", ["comment_content" => $comment_content]);

        $response->assertForbidden();
    }

    /**
     * Test that makes sures the user is sending some content as comment
     */
    public function testCantMakeACommentWithoutAContent() {
        $vent = Vent::factory()->allowComments()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->postJson("/api/vent/{$vent->id}/comment", ["comment_content" => null]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'comment_content'
                    ]
                ]);
    }
}
