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
        
        $userVote = null;
        if (Auth::check()) {
            $vote = TopicVote::where('topic_id', $id)
                ->where('user_id', Auth::id())
                ->first();
            $userVote = $vote ? $vote->vote_type : null;
        }

        return response()->json(['topic' => $topic, 'user_vote' => $userVote]);
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

        $userId = Auth::id();
        $existingVote = TopicVote::where('topic_id', $id)
            ->where('user_id', $userId)
            ->first();

        $topic = Topic::findOrFail($id);

        if ($existingVote) {
            if ($existingVote->vote_type === $request->vote_type) {
                $existingVote->delete();
                $request->vote_type === 'up' ? $topic->decrement('upvotes') : $topic->decrement('downvotes');
                return response()->json(['message' => 'Vote removed successfully']);
            }
            $existingVote->update(['vote_type' => $request->vote_type]);
            if ($request->vote_type === 'up') {
                $topic->increment('upvotes');
                $topic->decrement('downvotes');
            } else {
                $topic->increment('downvotes');
                $topic->decrement('upvotes');
            }
        } else {
            TopicVote::create([
                'topic_id' => $id,
                'user_id' => $userId,
                'vote_type' => $request->vote_type,
            ]);
            $request->vote_type === 'up' ? $topic->increment('upvotes') : $topic->increment('downvotes');
        }

        return response()->json(['message' => 'Vote recorded successfully']);
    }

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

    public function show($id)
    {
        $topic = Topic::with(['comments.user', 'user', 'category'])->findOrFail($id);
        
        $userVote = null;
        if (Auth::check()) {
            $vote = TopicVote::where('topic_id', $id)
                ->where('user_id', Auth::id())
                ->first();
            $userVote = $vote ? $vote->vote_type : null;
        }

        return response()->json(['topic' => $topic, 'user_vote' => $userVote]);
    }

    public function voteTopic(Request $request, $id)
    {
        $request->validate([
            'vote_type' => 'required|in:up,down',
        ]);

        $userId = Auth::id();
        $existingVote = TopicVote::where('topic_id', $id)
            ->where('user_id', $userId)
            ->first();

        $topic = Topic::findOrFail($id);

        if ($existingVote) {
            if ($existingVote->vote_type === $request->vote_type) {
                $existingVote->delete();
                $request->vote_type === 'up' ? $topic->decrement('upvotes') : $topic->decrement('downvotes');
                return response()->json(['message' => 'Vote removed successfully']);
            }
            $existingVote->update(['vote_type' => $request->vote_type]);
            if ($request->vote_type === 'up') {
                $topic->increment('upvotes');
                $topic->decrement('downvotes');
            } else {
                $topic->increment('downvotes');
                $topic->decrement('upvotes');
            }
        } else {
            TopicVote::create([
                'topic_id' => $id,
                'user_id' => $userId,
                'vote_type' => $request->vote_type,
            ]);
            $request->vote_type === 'up' ? $topic->increment('upvotes') : $topic->increment('downvotes');
        }

        return response()->json(['message' => 'Vote recorded successfully']);
    }

    public function voteComment(Request $request, $id)
    {
        $request->validate([
            'vote_type' => 'required|in:up,down',
        ]);

        $userId = Auth::id();
        $existingVote = CommentVote::where('comment_id', $id)
            ->where('user_id', $userId)
            ->first();

        $comment = Comment::findOrFail($id);

        if ($existingVote) {
            if ($existingVote->vote_type === $request->vote_type) {
                $existingVote->delete();
                $request->vote_type === 'up' ? $comment->decrement('upvotes') : $comment->decrement('downvotes');
                return response()->json(['message' => 'Vote removed successfully']);
            }
            $existingVote->update(['vote_type' => $request->vote_type]);
            if ($request->vote_type === 'up') {
                $comment->increment('upvotes');
                $comment->decrement('downvotes');
            } else {
                $comment->increment('downvotes');
                $comment->decrement('upvotes');
            }
        } else {
            CommentVote::create([
                'comment_id' => $id,
                'user_id' => $userId,
                'vote_type' => $request->vote_type,
            ]);
            $request->vote_type === 'up' ? $comment->increment('upvotes') : $comment->increment('downvotes');
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