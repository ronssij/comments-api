<?php

namespace App\Http\Controllers;

use App\Actions\CommentAction;
use App\Http\Requests\BlogCommentrequest;
use App\Http\Resources\CommentResource;
use App\Models\Blog;

class BlogCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogCommentrequest $request, Blog $blog, CommentAction $commentAction)
    {
        $comment = $commentAction->execute($blog, $request->validated());

        return CommentResource::make($comment);
    }
}
