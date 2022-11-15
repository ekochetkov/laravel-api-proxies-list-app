<?php

namespace App\Http\Controllers;

use App\Models\Proxy;

class ProxiesController extends Controller
{
    /**
     * Create a new Proxies instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get list of proxies
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $item = Proxy::factory()->count(rand(1,20))->make();
        
        return response()->json($item);
    }
}
