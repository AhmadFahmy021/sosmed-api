<?php

namespace App\Http\Controllers;

use App\Models\Stories;
use Illuminate\Http\Request;

class StoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stories = Stories::latest()->get();
        return view('stories.index', compact('stories')); // Assuming a view named 'stories.index'
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'caption' => 'required|string|max:255',
        ]);

        $story = new Stories;
        $story->foto = $request->file('foto')->store('public/stories');
        $story->user_id = $request->user()->id;
        //$story->user_id = auth()->user()->id; // Assuming authenticated user
        $story->caption = $request->caption;
        $story->save();

        return redirect()->route('stories.index')->with('success', 'Story created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stories $story)
    {
        return view('stories.show', compact('story')); // Assuming a view named 'stories.show'
    }

    /**
     * Update the specified resource in storage.
     */
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

        return redirect()->route('stories.index')->with('success', 'Story updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stories $story)
    {
        $story->delete();
        return redirect()->route('stories.index')->with('success', 'Story deleted successfully.');
    }
}