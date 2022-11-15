<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\ProxiesController;

class ProxiesTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test unauthorized list
     *
     * @return void
     */
    public function test_unathorized_list()
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

    /**
     * Test unauthorized export
     *
     * @return void
     */
    public function test_unathorized_export()
    {
        $this->getJson('/api/proxies/export')
             ->assertStatus(401);
    }

    /**
     * Test export without args
     *
     * @return void
     */
    public function test_export_without_args()
    {
        $token = AuthTest::get_authorized_jwt_token($this);

        $response = $this->withHeader('Authorization',"Bearer {$token}")
                         ->get('/api/proxies/export')
                         ->assertStatus(400)
                         ->assertExactJson(['message' => 'Field "format" is required']);
    }

    /**
     * Test export with bad format
     *
     * @return void
     */
    public function test_export_with_bad_format()
    {
        $token = AuthTest::get_authorized_jwt_token($this);

        $response = $this->withHeader('Authorization',"Bearer {$token}")
                         ->get('/api/proxies/export?format=some-bad-format')
                         ->assertStatus(400)
                         ->assertJsonFragment(['message' => 'Incurrect value of field "format"']);
    }

    /**
     * Test export with valid format
     *
     * @return void
     */
    public function test_export_with_valid_format()
    {
        $token = AuthTest::get_authorized_jwt_token($this);

        $formats = ProxiesController::get_formats();

        foreach($formats as $format => $format_params)
        {
        
            $content = $this->withHeader('Authorization',"Bearer {$token}")
                            ->get("/api/proxies/export?format={$format}")
                            ->assertDownload('proxies.csv')
                            ->content();

            $lines = explode("\n", $content);
            
            $this->assertGreaterThan(0, count($lines));

            foreach($lines as $line)
                $this->assertMatchesRegularExpression($format_params['regex_check'], $line);
        }        
    }
    
}
