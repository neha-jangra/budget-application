<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\{User, OtherDirectExpense, SubProjectData, Comment, CommentsAttachment, CommentsTaggedUser, CommentRead};
use Illuminate\Http\Request;
use App\Constants\RoleConstant;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\{Log, Storage};
use Auth;

class CommentsController extends Controller
{
    use WithFileUploads;

    public function sendComment(Request $request)
    {
        // Split tagged user IDs into an array
        $taggedUserIdsArray = explode(',', $request->input('taggedUserIds'));

        // Determine the parent_id
        $parentId = $request->input('parentId') ?? null;
        $commentArray = [
            'content' => $request->input('messageContent'),
            'user_id' => auth()->id(),
            'project_id' => $request->input('projectId'),
        ];
        $parentId = $request->input('parentId') ?? null;
        if($parentId!=null){
            $commentArray['parent_id'] = $parentId;
        }

        // Save the comment with the tagged users and attachments
        $comment = Comment::create($commentArray);

        if ($comment) {
            // Attach tagged users to the comment
            foreach ($taggedUserIdsArray as $value) {
                if (!empty($value)) {
                    CommentsTaggedUser::create([
                        'comment_id' => $comment->id,
                        'user_id' => trim($value),
                    ]);
                }
            }

            $attachments = [];
            // Store attachments related to the comment
            if ($request->hasFile('messageFiles')) {
                foreach ($request->file('messageFiles') as $file) {

                    $filePath = $file->store('attachments', 'public');
                    $attachment = CommentsAttachment::create([
                        'comment_id' => $comment->id,
                        'file_path' => Storage::url($filePath),
                        'file_type' => $file->getClientOriginalExtension(),
                    ]);
                    $attachments[] = $attachment;
                }
            }

            return response()->json([
                'success' => 'Comment added successfully!',
                'comment' => $comment,
                'user' => auth()->user(),
                'attachments' => $attachments
            ]);
        }

        return response()->json(['error' => 'Unable to add comment.'], 500);
    }

    public function sendReply(Request $request)
    {
        // Determine the parent_id
        $parentId = $request->input('parentId') ?? null;
        $commentArray = [
            'content' => $request->input('messageContent'),
            'user_id' => auth()->id(),
            'project_id' => $request->input('projectId'),
        ];
        $parentId = $request->input('parentId') ?? null;
        if($parentId!=null){
            $commentArray['parent_id'] = $parentId;
        }

        // Save the comment with the tagged users and attachments
        $comment = Comment::create($commentArray);
        if ($comment) {
             $attachments = [];
            // Store attachments related to the comment
            if ($request->hasFile('messageFiles')) {
                foreach ($request->file('messageFiles') as $file) {

                    $filePath = $file->store('attachments', 'public');
                    $attachment = CommentsAttachment::create([
                        'comment_id' => $comment->id,
                        'file_path' => Storage::url($filePath),
                        'file_type' => $file->getClientOriginalExtension(),
                    ]);
                    $attachments[] = $attachment;
                }
            }
            // Attach tagged users to the comment
            foreach ($taggedUserIdsArray as $value) {
                if (!empty($value)) {
                    CommentsTaggedUser::create([
                        'comment_id' => $comment->id,
                        'user_id' => trim($value),
                    ]);
                }
            }
            return response()->json([
                'success' => 'Reply added successfully!',
                'comment' => $comment,
                'user' => auth()->user(),
                'attachments' => $attachments
            ]);
        }
        return response()->json(['error' => 'Unable to add comment.'], 500);
    }

    public function getComments($projectId)
    {
        // Fetch comments and their replies
        $comments = Comment::where('project_id', $projectId)
            ->whereNull('parent_id')
            ->with('replies', 'attachments')
            ->get();

        return response()->json($comments);
    }

    public function getCommentsUsers()
    {
        $users = User::with(['roles'])->whereHas(
            'roles',
            function ($roles) {
                $roles->whereNotIn('role_id', [RoleConstant::DONOR, RoleConstant::EMPLOYEE, RoleConstant::CONSULTANT, RoleConstant::SUBGRANTEE]);
            }
        )->orderBy('name', 'asc')->get();
        return response()->json($users);
    }

    public function editComment(Request $request, $commentId)
    {

        $comment = Comment::findOrFail($commentId);
        $comment->content = $request->input('content');

        // Handle removed files
        $removedFiles = json_decode($request->input('removedFiles'), true);
        if (!empty($removedFiles)) {
            foreach ($removedFiles as $fileId) {
                $attachment = CommentsAttachment::find($fileId);
                if ($attachment) {
                    Storage::disk('public')->delete($attachment->file_path);
                    $attachment->delete();
                }
            }
        }

        $attachments = [];
        // Store new attachments related to the comment
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('attachments', 'public');
                $attachment = CommentsAttachment::create([
                    'comment_id' => $comment->id,
                    'file_path' => Storage::url($filePath),
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
                $attachments[] = $attachment;
            }
        }

        if ($comment->save()) {
            return response()->json([
                'success' => 'Comment updated successfully!',
                'comment' => $comment,
                'attachments' => $comment->attachments
            ]);
        }
        return response()->json(['error' => 'Unable to update comment.'], 500);
    }


    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['error' => 'Comment not found.'], 404);
        }
        $comment->delete();
        return response()->json(['success' => 'Comment deleted successfully!']);
    }

    public function getUnreadCommentsCount(Request $request)
    {
        $userId = Auth::user()->id;
        $unreadCount = Comment::where('project_id', $request->project_id)->where('user_id', '!=', $userId)
            ->whereDoesntHave('reads', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

    public function markCommentsAsRead(Request $request)
    {
        $userId = Auth::user()->id;
        $projectId = $request->project_id;

        $comments = Comment::where('project_id', $projectId)
            ->whereDoesntHave('reads', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        foreach ($comments as $comment) {
            CommentRead::create([
                'user_id' => $userId,
                'comment_id' => $comment->id,
            ]);
        }

        return response()->json(['status' => 'success']);
    }


}
