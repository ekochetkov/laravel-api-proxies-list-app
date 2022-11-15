<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**                                                                                                     
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proxy>                           
 */
class ProxyFactory extends Factory
{
    /**                                                                                                 
     * Define the model's default state.                                                                
     *                                                                                                  
     * @return array<string, mixed>                                                                     
     */
    public function definition()
    {
     	return [
            'ip'       => fake()->ipv4(),
            'port'     => str(rand(10000, 65535)),
            'login'    => fake()->username(),
            'password' => fake()->password()
        ];
    }

}
