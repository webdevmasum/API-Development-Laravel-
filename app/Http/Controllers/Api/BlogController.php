<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index()
    {
        // Fetches all blogs with their associated category
        $blogs = Blog::with('category')->get();
        return response()->json($blogs);
    }

    public function store(Request $request)
    {
        // Validates input data
        $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
        ]);

        // Attempts to store the blog within a transaction
        DB::beginTransaction();
        try {
            $blog = Blog::create($request->all());
            DB::commit();
            return response()->json($blog, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Blog creation failed'], 500);
        }
    }

    public function show($id)
    {
        // Finds and returns a specific blog
        $blog = Blog::with('category')->find($id);

        if (!$blog) {
            return response()->json(['error' => 'Blog not found'], 404);
        }

        return response()->json($blog);
    }

    public function update(Request $request, $id)
    {
        // Finds the blog by ID and validates input data
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['error' => 'Blog  not found'], 404);
        }

        $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
        ]);

        // Attempts to update the blog within a transaction
        DB::beginTransaction();
        try {
            $blog->update($request->all());
            DB::commit();
            return response()->json($blog);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Blog  update failed'], 500);
        }
    }

    public function destroy($id)
    {
        // Finds the blog by ID and deletes it within a transaction
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['error' => 'Blog not found'], 404);
        }

        DB::beginTransaction();
        try {
            $blog->delete();
            DB::commit();
            return response()->json(['message' => 'Blog deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Blog deletion failed'], 500);
        }
    }
}

