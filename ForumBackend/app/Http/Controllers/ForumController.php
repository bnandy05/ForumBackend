<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Comment;
use App\Models\TopicVote;
use App\Models\CommentVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $query = Topic::query();

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('order_by') && $request->order_by === 'upvotes') {
            $query->orderBy('upvotes', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $topics = $query->with('user', 'category')->paginate(10);
        return response()->json($topics);
    }

    public function createTopic(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $topic = Topic::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Topic created successfully', 'topic' => $topic]);
    }

    public function show($id)
    {
        $topic = Topic::with(['comments.user', 'user', 'category'])->findOrFail($id);
        return response()->json($topic);
    }
}