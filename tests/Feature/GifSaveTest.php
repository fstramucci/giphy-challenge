<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class GifSaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_save()
    {
        $response = $this->postJson('/api/gif/save');
        $response->assertStatus(401);
    }

    public function test_valid_save()
    {
        // Using create() instead of make() so it persists in the database
        $user = User::factory()->create();
        $gif_id = 'something';
        $alias = 'Test alias';

        $response = $this->actingAs($user)->postJson('/api/gif/save', [
            'gif_id' => $gif_id,
            'user_id' => $user->id,
            'alias' => $alias
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('favourites', [
            'gif_id' => $gif_id,
            'user_id' => $user->id,
            'alias' => $alias
        ]);
    }

    public function test_invalid_save()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->postJson('/api/gif/save', [
            'gif_id' => '',
            'user_id' => 999,
            'alias' => Str::random(300)
        ]);
        
        $response->assertStatus(422);
    }
}
