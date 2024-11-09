<?php

namespace App\Http\Controllers\Api;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $category = BlogCategory::create($request->all());
            DB::commit();
            return response()->json($category, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Category  creation failed'], 500);
        }
    }

    public function show($id)
    {
        $category = BlogCategory::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category  not found'], 404);
        }

        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $category->update($request->all());
            DB::commit();
            return response()->json($category);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Category update failed'], 500);
        }
    }

    public function destroy($id)
    {
        $category = BlogCategory::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        DB::beginTransaction();

        try {
            $category->delete();
            DB::commit();
            return response()->json(['message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Category deletion failed'], 500);
        }
    }
}
