<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helper;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\PersonalAccessTokenResult;

class LoginTest extends TestCase
{
    use RefreshDatabase, Helper;

    public function test_create_personal_access_token()
    {
        $this->createPersonalClient();

        $personal_access_token = User::factory()->create()->createToken('test');

        $this->assertInstanceOf(PersonalAccessTokenResult::class, $personal_access_token);

        $this->assertObjectHasProperty('accessToken', $personal_access_token);
        
        $this->assertObjectHasProperty('token', $personal_access_token);
    }

    public function test_successful_login()
    {
        $this->createPersonalClient();

        $user = User::factory()->create([
            'password' => bcrypt($password = 'testing'),
        ]);
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $response->assertStatus(200);
    }

    public function test_bad_login()
    {
        $this->createPersonalClient();

        $user = User::factory()->create([
            'password' => bcrypt($password = 'testing'),
        ]);
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'bad_password'
        ]);

        $response->assertStatus(401);
    }
}
