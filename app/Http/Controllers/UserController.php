<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::with('postingans', 'stories')->get(), 200);
        // return response()->json(User::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8',
            'bio' => 'nullable|string',
            'no_hp' => 'required|string|max:15',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // if ($request->hasFile('foto_profile')) {
        //     $validated['foto_profile'] = $request->file('foto_profile')->store('profile_pictures', 'public');
        // }
        $filePath = $request->file('foto_profile')->store('profile_pictures', 'public');
        $validated['foto_profile'] = $filePath;
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        Log::error($user);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('postingans', 'stories')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|unique:users,username,' . $id . '|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id . '|max:255',
            'password' => 'sometimes|required|min:8',
            'bio' => 'nullable|string',
            'no_hp' => 'sometimes|required|string|max:15',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_profile')) {
            if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
                Storage::disk('public')->delete($user->foto_profile);
            }

            $validated['foto_profile'] = $request->file('foto_profile')->store('profile_pictures', 'public');
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }


        if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
            Storage::disk('public')->delete($user->foto_profile);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
