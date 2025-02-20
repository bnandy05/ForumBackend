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
    public function getTopics(Request $request)
    {
        $query = Topic::query();

        if ($request->has('my_topics') && $request->my_topics) {
            $user = $request->user();
            $query->where('user_id', $user->id);
        }

        if ($request->has('user_topics') && $request->user_topics) {
            $query->where('user_id', $request->user_topics);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('order_by')) {
            switch ($request->order_by) {
                case 'upvotes':
                    $query->orderByRaw('upvotes - downvotes DESC');
                    break;
                case 'upvotes_asc':
                    $query->orderByRaw('upvotes - downvotes ASC');
                    break;
                case 'created_at':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'asc');
            }
        } else {
            $query->orderBy('created_at', 'asc');
        }

        $topics = $query->with('user:id,name', 'category')->paginate(10);

        $user = $request->user();
        $userVotes = [];

        if ($user) {
            $topicIds = $topics->pluck('id')->toArray();

            $votes = TopicVote::whereIn('topic_id', $topicIds)
                ->where('user_id', $user->id)
                ->get()
                ->keyBy('topic_id');

            $userVotes = collect($topicIds)->mapWithKeys(function ($topicId) use ($votes) {
                return [$topicId => $votes->has($topicId) ? $votes[$topicId]->vote_type : null];
            })->toArray();
        }

        return response()->json([
            'topics' => $topics,
            'user_votes' => $userVotes,
        ]);
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

    public function getTopic(Request $request, $id)
    {
        $user = $request->user();

        $topic = Topic::with(['comments.user:id,name', 'user:id,name', 'category'])->findOrFail($id);

        $userVote = null;
        if ($user) {
            $vote = TopicVote::where('topic_id', $id)
                ->where('user_id', $user->id)
                ->first();
            $userVote = $vote ? $vote->vote_type : null;
        }

        $userCommentVotes = [];
        if ($user) {
            $commentIds = $topic->comments->pluck('id')->toArray();

            $commentVotes = CommentVote::whereIn('comment_id', $commentIds)
                ->where('user_id', $user->id)
                ->get()
                ->keyBy('comment_id');

            $userCommentVotes = collect($commentIds)->mapWithKeys(function ($commentId) use ($commentVotes) {
                return [$commentId => $commentVotes->has($commentId) ? $commentVotes[$commentId]->vote_type : null];
            })->toArray();
        }

        return response()->json([
            'topic' => $topic,
            'user_vote' => $userVote,
            'user_comment_votes' => $userCommentVotes,
        ]);
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

        $user = $request->user();
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], 404);
        }

        $existingVote = TopicVote::where('topic_id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === $request->vote_type) {
                $existingVote->delete();
                $this->updateVoteCount($topic);
                return response()->json(['message' => 'Vote removed successfully']);
            }

            $existingVote->update(['vote_type' => $request->vote_type]);
        } else {
            TopicVote::create([
                'topic_id' => $id,
                'user_id' => $user->id,
                'vote_type' => $request->vote_type,
            ]);
        }

        $this->updateVoteCount($topic);
        return response()->json(['message' => 'Vote recorded successfully']);
    }

    private function updateVoteCount(Topic $topic)
    {
        $topic->upvotes = TopicVote::where('topic_id', $topic->id)->where('vote_type', 'up')->count();
        $topic->downvotes = TopicVote::where('topic_id', $topic->id)->where('vote_type', 'down')->count();
        $topic->save();
    }


    public function voteComment(Request $request, $id)
    {
        $request->validate([
            'vote_type' => 'required|in:up,down',
        ]);

        $user = $request->user();
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $existingVote = CommentVote::where('comment_id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === $request->vote_type) {
                $existingVote->delete();
                $this->updateCommentVoteCount($comment);
                return response()->json(['message' => 'Vote removed successfully']);
            }

            $existingVote->update(['vote_type' => $request->vote_type]);
        } else {
            CommentVote::create([
                'comment_id' => $id,
                'user_id' => $user->id,
                'topic_id' => $comment->topic_id,
                'vote_type' => $request->vote_type,
            ]);
        }

        $this->updateCommentVoteCount($comment);
        return response()->json(['message' => 'Vote recorded successfully']);
    }

private function updateCommentVoteCount(Comment $comment)
    {
        $comment->upvotes = CommentVote::where('comment_id', $comment->id)->where('vote_type', 'up')->count();
        $comment->downvotes = CommentVote::where('comment_id', $comment->id)->where('vote_type', 'down')->count();
        $comment->save();
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
