<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class GifShowTest extends TestCase
{
    
    public function test_unauthenticated_show()
    {
        $response = $this->getJson('/api/gif/show');
        $response->assertStatus(401);
    }

    public function test_valid_show()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->getJson('/api/gif/something');

        // It doesn't throw 404 because that's part of the Giphy response, not Laravel's
        $response->assertStatus(200);
    }

    public function test_invalid_show()
    {
        $user = User::factory()->make();

        // ID is too long, should not pass validation
        $response = $this->actingAs($user)->getJson('/api/gif/'.Str::random(65));

        $response->assertStatus(422);
    }
}
