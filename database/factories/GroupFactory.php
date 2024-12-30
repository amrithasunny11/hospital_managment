<?php

 
namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'parent_id' => null, // or you can set up logic to create nested groups
            'description' => $this->faker->sentence,
        ];
    }
}