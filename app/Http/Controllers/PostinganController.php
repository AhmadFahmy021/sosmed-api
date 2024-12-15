<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostinganResource;
use App\Http\Resources\UserResource;
use App\Models\Postingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostinganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postingan = Postingan::with('user')->get();
        // $data = ["message" => 200, "data" => $postingan];
        return response()->json($postingan, 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info($request);
        // dd($request);
        $validated = $request->validate([
                "foto" => 'mimes:png,jpg,jpeg|required|max:2048',
                "user_id" => 'required',
                'description' => 'required',
                'like' => 'required',
            ]);
        if ($request->hasFile("foto")) {
            $validated["foto"] = $request->file("foto")->store("postingans", "public");
        }
        $post = Postingan::create($validated);
        return response()->json($post, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($postingan)
    {
       $postingan = Postingan::find($postingan);
       if ($postingan == null) {
            return response()->json(["message" => "Data Not Found"], 404);
        }
        return response()->json($postingan, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $postingan)
    {
        // return response()->json($request);
        // Log::info($request);
        $data = Postingan::find($postingan);
        if ($data == null)  {
            return response()->json(["message" => "Data not found"], 404);
        }

        $validated = $request->validate([
            "description" => "required",
        ]);


        $data->update($validated);
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $postingan)
    {
        $data = Postingan::find($postingan);
        if ($data == null) {
            return response()->json(["message" => "Data Not Found"], 404);
        }

        if ($data->foto && Storage::exists($data->foto)) {

            Storage::delete($data->foto);
        }
        // $data->delete();
        return response()->json(["message" => "Data deleted Successfully"], 200);
    }

    public function liked($postingan) {
        $postingan = Postingan::find($postingan);
        if ($postingan == null) {
            return response()->json(["massage" => "Like Data Successfully"], 404);
        }
        $like = $postingan->like;
        $like++;
        $postingan->update(["like"=> $like]);
        return response()->json($postingan, 200);
    }

    public function unliked($postingan) {
        $postingan = Postingan::find($postingan);
        if ($postingan == null) {
            return response()->json(["massage" => "Like Data Successfully"], 404);
        }
        $unlike = $postingan->like;
        if ($unlike > 0) {
            $unlike--;
        }
        $postingan->update(["like"=> $unlike]);
        return response()->json($postingan, 200);
    }
}
