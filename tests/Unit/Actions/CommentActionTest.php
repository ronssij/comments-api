<?php

namespace Tests\Unit\Actions;

use Tests\TestCase;
use App\Models\Blog;
use App\Actions\CommentAction;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;

class CommentActionTest extends TestCase
{   
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->blog = factory(Blog::class)->create();
    }

    /** @test */
    function testCommentActionMockery()
    {
        /** @var CommentAction */
        $mock = Mockery::mock(CommentAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('execute')
                ->once()
                ->andReturn(Comment::class);
        });
        
        $mock->execute($this->blog, [
            'blog_id'  => $this->blog->id,
            'username' => 'cjronxel',
            'comment'  => 'This is a test comment for a blog'
        ]);
    }

    /** @test */
    public function itShouldCreateABlogComment()
    {
        $comment = app(CommentAction::class)->execute($this->blog, [
            'blog_id'  => $this->blog->id,
            'username' => 'cjronxel',
            'comment'  => 'This is a test comment for a blog'
        ]);

        $this->assertDatabaseHas('comments', [
            'id'        => $comment->id,
            'blog_id'   => $this->blog->id,
            'parent_id' => null,
            'comment'   => $comment->comment
        ]);
    }

     /** @test */
     public function itShouldReplyABlogComment()
     {
        $firstcomment = app(CommentAction::class)->execute($this->blog, [
            'blog_id'  => $this->blog->id,
            'username' => 'cjronxel',
            'comment'  => 'This is a test comment for a blog'
        ]);
 
        $secondcomment = app(CommentAction::class)->execute($this->blog, [
            'blog_id'  => $this->blog->id,
            'comment_id' => $firstcomment->id,
            'username' => 'otheruser',
            'comment'  => 'This is a reply to comment from a blog'
        ]);

        $this->assertDatabaseHas('comments', [
            'id'        => $secondcomment->id,
            'blog_id'   => $this->blog->id,
            'parent_id' => $firstcomment->id,
            'comment'   => $secondcomment->comment
        ]);
     }
}
