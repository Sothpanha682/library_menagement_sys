<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'remember' => 'sometimes|boolean',
        ]);

        $remember = $request->boolean('remember');

        unset($credentials['remember']);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => Auth::user(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
        ], 401);
    }

    /**
     * Get authenticated user
     */
    public function getUser(Request $request)
    {
        if (Auth::check()) {
            return response()->json([
                'success' => true,
                'user' => Auth::user(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Not authenticated',
        ], 401);
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated',
            ], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated',
            ], 401);
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * Upload profile image
     */
    public function uploadProfileImage(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated',
            ], 401);
        }

        $validated = $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $user = Auth::user();

        // Delete old profile image if exists
        if ($user->profile_image) {
            Storage::delete($user->profile_image);
        }

        // Store new profile image
        $path = $request->file('profile_image')->store('profile-images', 'public');
        $user->update(['profile_image' => '/storage/' . $path]);

        return response()->json([
            'success' => true,
            'message' => 'Profile image uploaded successfully',
            'image_path' => $user->profile_image,
        ]);
    }

    /**
     * Get system logo
     */
    public function getLogo()
    {
        $logo = DB::table('settings')->first();

        return response()->json([
            'success' => true,
            'logo' => $logo ?: ['logo_path' => null, 'logo_name' => 'LibSys'],
        ]);
    }

    /**
     * Upload system logo
     */
    public function uploadLogo(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated',
            ], 401);
        }

        $validated = $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'name' => 'required|string|max:255',
        ]);

        // Delete old logo if exists
        $existingLogo = DB::table('settings')->first();
        if ($existingLogo && $existingLogo->logo_path) {
            Storage::delete($existingLogo->logo_path);
        }

        // Store new logo
        $path = $request->file('logo')->store('logos', 'public');
        $logoPath = '/storage/' . $path;

        if ($existingLogo) {
            DB::table('settings')->update([
                'logo_path' => $logoPath,
                'logo_name' => $validated['name'],
            ]);
        } else {
            DB::table('settings')->insert([
                'logo_path' => $logoPath,
                'logo_name' => $validated['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logo uploaded successfully',
            'logo_path' => $logoPath,
            'logo_name' => $validated['name'],
        ]);
    }
}
