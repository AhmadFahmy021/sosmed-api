<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Stories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stories = Stories::latest()->get();
        return response()->json($stories); // Berikan respon JSON
    }

    public function store(Request $request)
    {
        Log::info('Request received', $request->all());

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'caption' => 'required|string|max:255',
        ]);

        $story = new Stories;
        $story->foto = $request->file('foto')->store('public/stories');
        $story->user_id = $request->user()->id; // Pastikan user autentikasi valid
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