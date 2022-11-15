<?php

namespace App\Http\Controllers;

use App\Models\Proxy;
use \Illuminate\Http\Request;

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
        $list = Proxy::factory()->count(rand(1,20))->make();
        
        return response()->json($list);
    }

    /**
     * Get formats for proxies list
     *
     * @return Array
     */
    public static function get_formats()
    {
        return [
            'ip:port@login:password' =>
                [
                    'format' => function ($proxy)
                    {
                        return sprintf('%s:%s@%s:%s'
                                       , $proxy['ip']
                                       , $proxy['port']
                                       , $proxy['login']
                                       , $proxy['password']);
                    },
                    'regex_check' => '/^(?:\d{1,3}\.){3}\d{1,3}\:\d+@.+\:.+$/'
                ],
            
            'ip:port' =>
                [
                    'format' => function ($proxy)
                    {
                        return $proxy['ip'] . ":" . $proxy['port'];
                    },
                    'regex_check' => '/^(?:\d{1,3}\.){3}\d{1,3}\:\d+$/'
                ],
            
            'ip@login:password' => [
                'format' => function ($proxy)
                {
                    return sprintf('%s@%s:%s'
                                   , $proxy['ip']
                                   , $proxy['login']
                                   , $proxy['password']);                
                },
                'regex_check' => '/^(?:\d{1,3}\.){3}\d{1,3}\@.+\:.+$/'
           ]
        ];
    }
    
    /**
     * Export list of proxies
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        $format = request()->query('format');

        if( empty($format) )
            return response()->json(['message' => 'Field "format" is required'], 400);

        $formats = $this::get_formats();
        
        $valid_formats = array_keys($formats);
        
        if( !in_array($format, $valid_formats) )
            return response()->json(
                [
                    'message'       => 'Incurrect value of field "format"',
                    'valid_formats' => $valid_formats
                ], 400);
            
        $list = Proxy::factory()->count(rand(5,40))->make();

        $proxies_format = [];

        foreach($list as $proxy)
            $proxies_format []= $formats[$format]['format']($proxy);

        $content = implode("\n", $proxies_format);

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="proxies.csv"',
        ];

        return response($content, 200, $headers);
    }    
}
