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

            return Comment::create($data, $parent);
        }

        return $blog->comments()->create($data);
    }
}