<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BlogResource extends Resource
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
            'title'      => $this->title,
            'body'       => $this->body,
            'created_at' => $this->created_at->diffForHumans(),

            // Relationships
            'comments' => CommentResource::collection($this->whenLoaded('comments'))
        ];
    }
}
