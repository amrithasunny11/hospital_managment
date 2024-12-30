<?php

 
namespace Database\Factories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

class HospitalFactory extends Factory
{
    protected $model = Hospital::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word
        ];
    }
}