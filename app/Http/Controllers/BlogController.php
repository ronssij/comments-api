<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __invoke()
    {
        $blogs = Blog::with([
                'comments' => function ($query) {
                    return $query->parentOnly()->with('replies');
                }
            ])
            ->latest()
            ->get();

        return BlogResource::collection($blogs);
    }
}
