<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    public function run()
    {
        // Create parent groups
        $parentGroups = Group::factory()->count(5)->create();

        // Create child groups for each parent group
        $parentGroups->each(function ($parentGroup) {
            Group::factory()->count(3)->create([
                'parent_id' => $parentGroup->id,
            ]);
        });
    }
}
