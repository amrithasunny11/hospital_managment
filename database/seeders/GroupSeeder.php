<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\Hospital; // Ensure Hospital is imported

class GroupSeeder extends Seeder
{
    public function run()
    {
        // Create hospitals (if required)
        $hospitals = Hospital::factory()->count(3)->create(); // Adjust count as needed

        // Create parent groups for each hospital
        $hospitals->each(function ($hospital) {
            $parentGroups = Group::factory()->count(5)->create([
                'hospital_id' => $hospital->id,  // Assign hospital_id to each parent group
            ]);

            // Create child groups for each parent group
            $parentGroups->each(function ($parentGroup) use ($hospital) {
                Group::factory()->count(3)->create([
                    'parent_id' => $parentGroup->id,
                    'hospital_id' => $hospital->id,  // Assign the same hospital_id to child groups
                ]);
            });
        });
    }
}
