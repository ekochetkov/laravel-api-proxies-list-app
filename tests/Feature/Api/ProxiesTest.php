<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
//use App\Models\Use;

class ProxiesTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test unauthorized
     *
     * @return void
     */
    public function test_unathorized()
    {
        $this->getJson('/api/proxies/list')
             ->assertStatus(401);
    }

    /**
     * Test get list
     *
     * @return void
     */
    public function test_list()
    {
        $token = AuthTest::get_authorized_jwt_token($this);

        $response = $this->withHeader('Authorization',"Bearer {$token}")
                         ->getJson('/api/proxies/list')
                         ->assertStatus(200);

        $list = $response->json();

        $this->assertGreaterThan(0, count($list));
        
        foreach($list as $item)
            $response->assertJsonStructure(['ip', 'port', 'login', 'password'], $item);
    }
}
