<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Stories;
use Illuminate\Http\Request;

class StoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stories = Stories::with('user')->latest()->get(); // Mengambil stories dengan informasi user terkait
        return response()->json($stories);
    }

    public function store(Request $request)
    {
        Log::info('Request received', $request->all());

        // Validasi input
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'caption' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', // Pastikan user_id valid
        ]);

        // Menyimpan story dengan relasi ke user
        $story = new Stories;
        $story->foto = $request->file('foto')->store('public/stories');
        $story->user_id = $request->input('user_id'); // Bisa menggunakan request 'user_id' atau auth()->user()->id
        $story->caption = $request->caption;
        $story->save();

        return response()->json(['message' => 'Story created successfully.'], 201);
    }

    public function update(Request $request, Stories $story)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'caption' => 'required|string|max:255',
        ]);

        if ($request->hasFile('foto')) {
            $story->foto = $request->file('foto')->store('public/stories');
        }

        $story->caption = $request->caption;
        $story->save();

        return response()->json(['message' => 'Story updated successfully.', 'data' => $story]);
    }

    public function destroy(Stories $story)
    {
        $story->delete();
        return response()->json(['message' => 'Story deleted successfully.']);
    }
}