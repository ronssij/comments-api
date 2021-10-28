<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CommentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'parent_id'  => $this->parent_id,
            'blog_id'    => $this->blog_id,
            'username'   => $this->username,
            'comment'    => $this->comment,
            'created_at' => $this->created_at->diffForHumans(),

            // Relationships
            'blog'    => BlogResource::make($this->whenLoaded('blog')),
            'replies' => self::collection($this->whenLoaded('replies'))
        ];
    }
}
