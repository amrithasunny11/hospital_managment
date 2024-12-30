<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Hospital;  // Ensure Hospital is imported
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'parent_id' => null,  // or you can set up logic to create nested groups
            'description' => $this->faker->sentence,
            'hospital_id' => Hospital::factory(),  // Create a hospital for each group
        ];
    }
}
