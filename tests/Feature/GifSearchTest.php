<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class GifSearchTest extends TestCase
{
    
    public function test_unauthenticated_search()
    {
        $response = $this->getJson('/api/gif/search');
        $response->assertStatus(401);
    }

    public function test_valid_search()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->getJson(
            route('gif.search', [
                'query' => 'cat', 'limit' => 10, 'offset' => 0
            ]));
        $response->assertStatus(200);
    }

    public function test_invalid_search()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->getJson(
            route('gif.search', [
                'query' => '', 'limit' => 1000, 'offset' => -10
            ]));
        $response->assertStatus(422);
    }
}
