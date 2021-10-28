<?php

namespace App\Actions;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Support\Arr;

class CommentAction
{
    public function execute(Blog $blog, $data)
    {
        $comment = Arr::get($data, 'comment_id', null);

        if ($comment) {
            $parent = $blog->comments()->find($comment);

            $data = array_merge($data, [
                'parent_id' => $comment
            ]);

            $comment = Comment::create($data, $parent);
            $comment->load('replies');

        } else {
            $comment = $blog->comments()->create($data);
            $comment->load('replies');
        }

        return $blog->comments()->with(['replies'])->find($comment->id);
    }
}