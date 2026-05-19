<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MemberController extends Controller
{
    /**
     * Get all members
     */
    public function index(): JsonResponse
    {
        try {
            $members = Member::orderBy('joined_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $members,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch members: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new member
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:members,email',
                'phone' => 'nullable|string|max:20',
                'status' => 'required|in:Active,Inactive',
            ]);

            $member = Member::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Member registered successfully',
                'data' => $member,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create member: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single member
     */
    public function show(Member $member): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $member,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch member: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a member
     */
    public function update(Request $request, Member $member): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:members,email,' . $member->id,
                'phone' => 'nullable|string|max:20',
                'status' => 'required|in:Active,Inactive',
            ]);

            $member->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Member updated successfully',
                'data' => $member,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update member: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a member
     */
    public function destroy(Member $member): JsonResponse
    {
        try {
            $member->delete();

            return response()->json([
                'success' => true,
                'message' => 'Member deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete member: ' . $e->getMessage(),
            ], 500);
        }
    }
}
