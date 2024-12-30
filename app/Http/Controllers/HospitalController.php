<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class HospitalController extends Controller
{
     

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
 
        try {
            $hospital = Hospital::create($validated);

            return response()->json([
                'message' => 'Hospital created successfully',
                'hospital' => $hospital
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the hospital',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $hospitals = Hospital::all();

            return response()->json([
                'hospitals' => $hospitals
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving hospitals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $hospital = Hospital::findOrFail($id);

            return response()->json([
                'hospital' => $hospital
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Hospital not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the hospital',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255', 
        ]);

        try {
            $hospital = Hospital::findOrFail($id);
            $hospital->update($validated);

            return response()->json([
                'message' => 'Hospital updated successfully',
                'hospital' => $hospital
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Hospital not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the hospital',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $hospital = Hospital::findOrFail($id);

            // Check if the hospital has any group
            if ($hospital->groups()->exists()) {
                return response()->json([
                    'message' => 'Cannot delete hospital with groups. Please delete groups first.'
                ], 400);
            }

            $hospital->delete();

            return response()->json([
                'message' => 'Hospital deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Hospital not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the hospital',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}