<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Hospital;

class GroupControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_create_group()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hospital = Hospital::factory()->create();  // Assuming hospital_id is required

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/groups', [
            'name' => 'New Group',
            'parent_id' => null,
            'description' => null,
            'hospital_id' => $hospital->id,  // Add hospital_id if necessary
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Group created successfully',
                'group' => [
                    'name' => 'New Group',
                    'parent_id' => null,
                    'description' => null,
                    'hospital_id' => $hospital->id,  // Ensure it matches
                ]
            ]);

        $this->assertDatabaseHas('groups', [
            'name' => 'New Group',
            'hospital_id' => $hospital->id,  // Verify hospital_id in database
        ]);
    }


    public function test_non_admin_cannot_create_group()
    {
        $user = User::factory()->create(['role' => 'user']);
        $hospital = Hospital::factory()->create();  // Assuming hospital_id is required

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/groups', [
            'name' => 'New Group',
            'parent_id' => null,
            'description' => null,
            'hospital_id' => $hospital->id,
        ]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);
    }

    public function test_admin_can_update_group()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $group = Group::factory()->create();
        $hospital = Hospital::factory()->create();  // Ensure hospital_id is passed if needed

        $response = $this->actingAs($admin, 'sanctum')->putJson("/api/groups/{$group->id}", [
            'name' => 'Updated Group',
            'parent_id' => null,
            'description' => 'Updated description',
            'hospital_id' => $hospital->id,  // Add hospital_id if needed
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Group updated successfully',
                'group' => [
                    'id' => $group->id,
                    'name' => 'Updated Group',
                    'parent_id' => null,
                    'description' => 'Updated description',
                    'hospital_id' => $hospital->id,  // Ensure the update includes hospital_id
                ]
            ]);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Updated Group',
            'description' => 'Updated description',
            'hospital_id' => $hospital->id,  // Verify hospital_id in database
        ]);
    }


    public function test_non_admin_cannot_update_group()
    {
        $user = User::factory()->create(['role' => 'user']);
        $group = Group::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/groups/{$group->id}", [
            'name' => 'Updated Group',
            'parent_id' => null,
            'description' => 'Updated description'
        ]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);
    }

    public function test_admin_can_delete_group()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $group = Group::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')->deleteJson("/api/groups/{$group->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Group deleted successfully'
            ]);

        $this->assertDatabaseMissing('groups', [
            'id' => $group->id  // Ensure the group was deleted from the database
        ]);
    }


    public function test_non_admin_cannot_delete_group()
    {
        $user = User::factory()->create(['role' => 'user']);
        $group = Group::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/groups/{$group->id}");

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);
    }

    // public function test_list_groups()
    // {
    //     $user = User::factory()->create();

    //     // Create hospitals and groups (some will have child groups)
    //     $hospital = Hospital::factory()->create();
    //     $parentGroup = Group::factory()->create(['hospital_id' => $hospital->id]);
    //     $childGroup = Group::factory()->create(['hospital_id' => $hospital->id, 'parent_id' => $parentGroup->id]);

    //     $response = $this->actingAs($user, 'sanctum')->getJson('/api/groups');

    //     // Assert status and structure
    //     $response->assertStatus(200)
    //         ->assertJsonStructure([
    //             'groups' => [
    //                 '*' => [
    //                     'id',
    //                     'name',
    //                     'parent_id',  // Ensure parent_id is present
    //                     'description',
    //                     'created_at',
    //                     'updated_at',
    //                     'children' => [
    //                         '*' => [
    //                             'id',
    //                             'name',
    //                             'parent_id',
    //                             'description',
    //                             'created_at',
    //                             'updated_at',
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ])
    //         ->assertJsonCount(1, 'groups');  // There should be 1 hospital with groups

    //     // Check if the hospital and its groups are present
    //     $response->assertJsonFragment([
    //         'id' => $hospital->id,
    //         'name' => $hospital->name,
    //     ]);

    //     // Validate the parent group
    //     $response->assertJsonFragment([
    //         'id' => $parentGroup->id,
    //         'name' => $parentGroup->name,
    //         'parent_id' => null,  // Ensure parent_id is included and is null for top-level groups
    //         'children' => [
    //             [
    //                 'id' => $childGroup->id,
    //                 'name' => $childGroup->name,
    //             ]
    //         ]
    //     ]);
    // }




    // public function test_view_group()
    // {
    //     $user = User::factory()->create();
    //     $group = Group::factory()->create();

    //     $response = $this->actingAs($user, 'sanctum')->getJson("/api/groups/{$group->id}");

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'group' => [
    //                 'id' => $group->id,
    //                 'name' => $group->name,
    //                 'parent_id' => $group->parent_id,
    //                 'description' => $group->description,
    //                 'created_at' => $group->created_at->toISOString(),
    //                 'updated_at' => $group->updated_at->toISOString(),
    //                 'children' => []  // Ensure the children array is present
    //             ]
    //         ]);
    // }
}
