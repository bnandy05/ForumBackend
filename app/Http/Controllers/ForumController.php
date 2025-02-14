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

        $topics = $query->with('user:id,name', 'category')->paginate(10);
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
        $topic = Topic::with(['comments.user', 'user:id,name', 'category'])->findOrFail($id);
        return response()->json($topic);
    }

    public function getCategories(Request $request)
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function addComment(Request $request, $topicId)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'topic_id' => $topicId,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment]);
    }

    public function voteTopic(Request $request, $id)
    {
        $request->validate([
            'vote_type' => 'required|in:up,down',
        ]);

        $existingVote = TopicVote::where('topic_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            return response()->json(['message' => 'You already voted on this topic'], 400);
        }

        $vote = TopicVote::create([
            'topic_id' => $id,
            'user_id' => Auth::id(),
            'vote_type' => $request->vote_type,
        ]);

        $topic = Topic::findOrFail($id);
        if ($request->vote_type === 'up') {
            $topic->increment('upvotes');
        } else {
            $topic->increment('downvotes');
        }

        return response()->json(['message' => 'Vote recorded successfully']);
    }
    public function voteComment(Request $request, $id)
    {
        $request->validate([
            'vote_type' => 'required|in:up,down',
        ]);

        $existingVote = CommentVote::where('comment_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            return response()->json(['message' => 'You already voted on this comment'], 400);
        }

        $vote = CommentVote::create([
            'comment_id' => $id,
            'user_id' => Auth::id(),
            'vote_type' => $request->vote_type,
        ]);

        $comment = Comment::findOrFail($id);
        if ($request->vote_type === 'up') {
            $comment->increment('upvotes');
        } else {
            $comment->increment('downvotes');
        }

        return response()->json(['message' => 'Vote recorded successfully']);
    }

    public function deleteAdminTopic($id)
    {
        $topic = Topic::findOrFail($id);

        if (Auth::user()->is_admin || Auth::id() === $topic->user_id) {
            $topic->delete();
            return response()->json(['message' => 'Topic deleted successfully']);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function deleteAdminComment($id)
    {
        $comment = Comment::findOrFail($id);

        if (Auth::user()->is_admin || Auth::id() === $comment->user_id) {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully']);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
    
    public function deleteTopic($id)
    {
        $topic = Topic::findOrFail($id);

        if ($topic->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json(['error' => 'Nincs jogosultságod törölni ezt a topikot.'], 403);
        }

        $topic->delete();

        return response()->json(['success' => 'Topik sikeresen törölve.'], 200);
    }

    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json(['error' => 'Nincs jogosultságod törölni ezt a kommentet.'], 403);
        }

        $comment->delete();

        return response()->json(['success' => 'Komment sikeresen törölve.'], 200);
    }
}
