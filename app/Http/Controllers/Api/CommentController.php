<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{

    // List all comments for a specific post
    public function index($postId)
    {
        try {
            $post = Post::findOrFail($postId);
            $comments = $post->comments()->get();

            return response()->json($comments, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Post not found'], 404);
        }
    }


    // Store a new comment
    public function store(Request $request, $postId)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $post = Post::findOrFail($postId);

            $comment = $post->comments()->create([
                'user_name' => $request->user_name,
                'content' => $request->content,
            ]);

            DB::commit();

            return response()->json(['message' => 'Comment added successfully', 'comment' => $comment], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to add comment', 'details' => $e->getMessage()], 500);
        }
    }

    // Retrieve a specific comment
    public function show($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            return response()->json($comment, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
    }

    // Update a specific comment
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $comment = Comment::findOrFail($id);

            $comment->update([
                'user_name' => $request->user_name,
                'content' => $request->content,
            ]);

            DB::commit();

            return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update comment', 'details' => $e->getMessage()], 500);
        }
    }

    // Delete a specific comment
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $comment = Comment::findOrFail($id);
            $comment->delete();

            DB::commit();

            return response()->json(['message' => 'Comment deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete comment', 'details' => $e->getMessage()], 500);
        }
    }
}
