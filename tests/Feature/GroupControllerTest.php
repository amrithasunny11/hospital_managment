<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Group;
use App\Models\User;
use App\Models\Hospital; // If hospital_id is required, ensure the hospital model is included.
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_create_group()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $hospital = Hospital::factory()->create(); // Assuming a hospital_id is required for group creation

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/groups', [
            'name' => 'New Group',
            'parent_id' => null,
            'description' => null,
            'hospital_id' => $hospital->id, // Add hospital_id if needed
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Group created successfully',
                'group' => [
                    'name' => 'New Group',
                    'parent_id' => null,
                    'description' => null,
                    'hospital_id' => $hospital->id, // Ensure the hospital_id is returned
                ]
            ]);

        $this->assertDatabaseHas('groups', [
            'name' => 'New Group',
            'hospital_id' => $hospital->id, // Check hospital_id in the database if required
        ]);
    }

    public function test_non_admin_cannot_create_group()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/groups', [
            'name' => 'New Group',
            'parent_id' => null,
            'description' => null
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Unauthorized'
            ]);
    }

    public function test_admin_can_update_group()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $group = Group::factory()->create();
        $hospital = Hospital::factory()->create(); // Ensure hospital_id is passed if needed

        $response = $this->actingAs($admin, 'sanctum')->putJson("/api/groups/{$group->id}", [
            'name' => 'Updated Group',
            'parent_id' => null,
            'description' => 'Updated description',
            'hospital_id' => $hospital->id, // Add hospital_id if required
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Group updated successfully',
                'group' => [
                    'id' => $group->id,
                    'name' => 'Updated Group',
                    'parent_id' => null,
                    'description' => 'Updated description',
                    'hospital_id' => $hospital->id, // Ensure the hospital_id is updated
                ]
            ]);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Updated Group',
            'description' => 'Updated description',
            'hospital_id' => $hospital->id, // Check hospital_id in the database
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
            ->assertJson([
                'message' => 'Unauthorized'
            ]);
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
            'id' => $group->id
        ]);
    }

    public function test_non_admin_cannot_delete_group()
    {
        $user = User::factory()->create(['role' => 'user']);
        $group = Group::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/groups/{$group->id}");

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Unauthorized'
            ]);
    }
}
