<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthorized
     *
     * @return void
     */
    public function test_unauthorized()
    {
        $response = $this->getJson('/api/auth/status');
        $response->assertStatus(401);
    }
    
    /**
     * Test registration
     *
     * @return void
     */
    public function test_registration()
    {
        $user = [
            "name"     => fake()->name(),
            "email"    => fake()->unique()->safeEmail(),
            "password" => fake()->password()
        ];
                
        $this->post('/api/auth/registration', $user)
             ->assertStatus(200)
             ->assertExactJson(['message' => 'Successfully registration!']);

        $user_db = User::where('email', $user['email'])->first();

        $this->assertEquals([ $user   ['name'], $user   ['email'] ],
                            [ $user_db['name'], $user_db['email'] ]);        
    }

    /**
     * Test login
     *
     * @return void
     */
    public function test_login()
    {
        $user = User::factory()->create();

        $post_args = ['email'    => $user->email,
                      'password' => 'password'];
    
        $response = $this->post('/api/auth/login', $post_args);
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    /**
     * Check status
     *
     * @return void
     */
    public function test_status()
    {
        $user = User::factory()->create();

        $post_args = ['email'    => $user->email,
                      'password' => 'password'];
    
        $token = $this->post('/api/auth/login', $post_args)
                      ->json(['access_token']);
        
        $this->withHeader('Authorization',"Bearer {$token}")
             ->getJson('/api/auth/status')
             ->assertStatus(200)
             ->assertExactJson(['status' => 'Authorized']);
    }

    /**
     * Check logout
     *
     * @return void
     */
    public function test_logout()
    {
        $user = User::factory()->create();

        $post_args = ['email'    => $user->email,
                      'password' => 'password'];
    
        $token = $this->post('/api/auth/login', $post_args)
                      ->json(['access_token']);
        
        $this->withHeader('Authorization',"Bearer {$token}")
             ->getJson('/api/auth/status')
             ->assertStatus(200)
             ->assertExactJson(['status' => 'Authorized']);

        $this->withHeader('Authorization',"Bearer {$token}")
             ->post('/api/auth/logout')
             ->assertStatus(200)
             ->assertExactJson(['message' => 'Successfully logged out']);            

        $this->getJson('/api/auth/status')
             ->assertStatus(401);        
    }    

    /**
     * Check refresh token
     *
     * @return void
     */
    public function test_refresh_token()
    {
        $user = User::factory()->create();

        $post_args = ['email'    => $user->email,
                      'password' => 'password'];
    
        $response = $this->post('/api/auth/login', $post_args);

        $token = $response->json(['access_token']);
        
        $response_refresh = $this->withHeader('Authorization',"Bearer {$token}")
                                ->post('/api/auth/refresh');

        
        $response_refresh
        ->assertStatus(200)
        ->assertJsonStructure([
            'access_token', 'token_type', 'expires_in'
        ]);
        
        
    }
    
}
