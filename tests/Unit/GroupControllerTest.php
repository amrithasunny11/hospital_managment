<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_create_group()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/groups', [
            'name' => 'New Group',
            'parent_id' => null,
            'description' => null
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Group created successfully',
                     'group' => [
                         'name' => 'New Group',
                         'parent_id' => null,
                         'description' => null
                     ]
                 ]);

        $this->assertDatabaseHas('groups', [
            'name' => 'New Group'
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
                 ->assertJson(['message' => 'Unauthorized']);
    }

    public function test_admin_can_update_group()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $group = Group::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')->putJson("/api/groups/{$group->id}", [
            'name' => 'Updated Group',
            'parent_id' => null,
            'description' => 'Updated description'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Group updated successfully',
                     'group' => [
                         'id' => $group->id,
                         'name' => 'Updated Group',
                         'parent_id' => null,
                         'description' => 'Updated description'
                     ]
                 ]);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Updated Group',
            'description' => 'Updated description'
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
            'id' => $group->id
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

    public function test_list_groups()
    {
        $user = User::factory()->create();
        Group::factory()->count(3)->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/groups');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'groups' => [
                         '*' => [
                             'id',
                             'name',
                             'parent_id',
                             'description',
                             'created_at',
                             'updated_at',
                             'children'
                         ]
                     ]
                 ]);
    }

    public function test_view_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/groups/{$group->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'group' => [
                         'id' => $group->id,
                         'name' => $group->name,
                         'parent_id' => $group->parent_id,
                         'description' => $group->description,
                         'created_at' => $group->created_at->toISOString(),
                         'updated_at' => $group->updated_at->toISOString(),
                         'children' => []
                     ]
                 ]);
    }
}
