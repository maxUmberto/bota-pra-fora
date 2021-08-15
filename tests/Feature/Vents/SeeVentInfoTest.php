<?php

namespace Tests\Feature\Vents;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Vent;
use App\Models\VentComment;
use App\Models\User;
use App\Models\Reaction;

class SeeVentInfoTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test if a user can view the infos about his vents
     *
     * @return void
     */
    public function test_see_vent_info()
    {
        $vent_info = $this->createVentWithInfos();
        $vent = $vent_info['vent'];
        $user = User::find($vent->user_id);

        $response = $this->actingAs($user)
                        ->get("api/vent/{$vent->id}/info");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'vent' => [
                        'id',
                        'user_id',
                        'vent_content',
                        'allow_comments',
                        'vent_comments' => [
                            '*' => [
                                'id',
                                'vent_id',
                                'user_id',
                                'comment_content'
                            ]
                        ],
                        'vent_views_count',
                        'reactions' => [
                            '*' => [
                                'name',
                                'icon_name'
                            ]
                        ]
                    ]
                ])
                ->assertJsonCount($vent_info['comments_qtt'], 'vent.vent_comments')
                ->assertJsonCount($vent_info['reactions_qtt'], 'vent.reactions');

        $response = json_decode($response->getContent());
        $this->assertEquals($vent_info['views_qtt'], $response->vent->vent_views_count);
    }

    /**
     * Test if a user cant view infos about others users ventings
     *
     * @return void
     */
    public function test_unable_to_see_vent_info_of_another_user() {
        $user = User::factory()->create();

        $vent_info = $this->createVentWithInfos();
        $vent = $vent_info['vent'];

        $response = $this->actingAs($user)
                        ->get("api/vent/{$vent->id}/info");

        $response->assertForbidden();
    }

    /**
     * Create a vent with infos like
     * - Vent comments
     * - Vent views
     * - Vent reactions
     * 
     * @return Array
     */
    private function createVentWithInfos(): Array {
        $views_qtt = rand(1,10);
        $comments_qtt = rand(0, $views_qtt);
        $reactions_qtt = rand(0, $views_qtt);

        $vent = Vent::factory()
                    ->hasVentComments($comments_qtt)
                    ->hasVentViews($views_qtt)
                    ->allowComments()->create();

        for($i = 0; $i < $reactions_qtt; $i++) {
            $user = User::factory()->create();
            $reaction = Reaction::inRandomOrder()->pluck('id')->first();
            $vent->reactions()->attach($reaction, ['user_id' => $user->id]);
        }

        return [
            'vent'          => $vent,
            'views_qtt'     => $views_qtt,
            'comments_qtt'  => $comments_qtt,
            'reactions_qtt' => $reactions_qtt
        ];
    }
}
