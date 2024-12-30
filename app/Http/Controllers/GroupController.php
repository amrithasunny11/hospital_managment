<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use App\Models\Hospital;

class GroupController extends Controller
{
    // Create a new group
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:groups,id',
                'hospital_id' => 'exists:hospitals,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $group = Group::create($request->only(['name', 'description', 'parent_id', 'hospital_id']));

            return response()->json([
                'message' => 'Group created successfully',
                'group' => $group
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the group',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Retrieve all groups (hierarchical structure)
    public function index()
    {
        try {
            $hospitals = Hospital::with(['groups' => function ($query) {
                $query->whereNull('parent_id')->with('children');
            }])->get();

            $formattedHospitals = $hospitals->map(function ($hospital) {
                return [
                    'id' => $hospital->id,
                    'name' => $hospital->name,
                    'address' => $hospital->address,
                    'groups' => $hospital->groups->map(function ($group) {
                        return $this->formatGroup($group);
                    }),
                ];
            });

            return response()->json([
                'hospitals' => $formattedHospitals
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving groups',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function formatGroup($group)
    {
        return [
            'id' => $group->id,
            'name' => $group->name,
            'description' => $group->description,
            'children' => $group->children->map(function ($child) {
                return $this->formatGroup($child);
            }),
        ];
    }

    // Retrieve a specific group by ID
    public function show($id)
    {
        try {
            // Retrieve the group along with its children
            $group = Group::with('children')->findOrFail($id);

            // Format the response (optional if needed)
            $formattedGroup = $this->formatGroup($group);

            return response()->json([
                'group' => $formattedGroup
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the group',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Update a group
    public function update(Request $request, $id)
    {
        try {
            $group = Group::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:groups,id|not_in:' . $id, // Prevent setting itself as parent,
                'hospital_id' => 'exists:hospitals,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $group->update($request->only(['name', 'description', 'parent_id','hospital_id']));

            return response()->json([
                'message' => 'Group updated successfully',
                'group' => $group
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the group',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete a group
    public function destroy($id)
    {
        try {
            $group = Group::findOrFail($id);

            // Check if the group has any children
            if ($group->children()->exists()) {
                return response()->json([
                    'message' => 'Cannot delete group with child groups. Please delete child groups first.'
                ], 400);
            }

            $group->delete();

            return response()->json([
                'message' => 'Group deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the group',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
