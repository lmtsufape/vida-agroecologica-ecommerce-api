<?php

namespace Database\Factories;

use App\Models\Produtor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produtor>
 */
class ProdutorFactory extends Factory
{
    protected $model = Produtor::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'distancia_feira' => fake()->randomFloat(2,0,300),
            'distancia_semana' => fake()->randomFloat(2,0,300),
        ];

    }
    public function configure()
    {
      
        return $this->afterMaking(function (Produtor $produtor) {
            $produtor->save();
            $produtor->user()->create([ 'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), 
            'remember_token' => Str::random(10),
            'telefone'=> fake()->numerify('##-#####-####')
            ]);
            $produtor->save();
        });
    }

}
