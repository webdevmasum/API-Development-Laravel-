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

        $blogs = Blog::with('category')->get();
        return response()->json($blogs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
        ]);

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
        $blog = Blog::with('category')->find($id);

        if (!$blog) {
            return response()->json(['error' => 'Blog not found'], 404);
        }

        return response()->json($blog);
    }

    public function update(Request $request, $id)
    {
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

