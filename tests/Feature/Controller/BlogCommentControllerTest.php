<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use App\Models\Blog;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogCommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->blog = factory(Blog::class,)->create();
    }

    /** @test */
    public function itShouldValidateUsernameField()
    {
        $response = $this->postJson(route('blog.comment', [
            'blog' => $this->blog
        ]), [
            'blog_id'  => $this->blog->id,
            'username' => null,
            'comment'  => 'This is a test comment for a blog'
        ]);

        $response->assertStatus(422);
        $response->assertSeeText('The username field is required.');
    }

    /** @test */
    public function itShouldValidateBlogIdField()
    {
        $response = $this->postJson(route('blog.comment', [
            'blog' => $this->blog
        ]), [
            'blog_id'  => null,
            'username' => 'cjronxel',
            'comment'  => 'This is a test comment for a blog'
        ]);

        $response->assertStatus(422);
        $response->assertSeeText('The blog id field is required.');
    }

    /** @test */
    public function userCanAddCommentOnABlogPost()
    {
        $response = $this->postJson(route('blog.comment', [
                'blog' => $this->blog
            ]), [
                'blog_id'  => $this->blog->id,
                'username' => 'cjronxel',
                'comment'  => 'This is a test comment for a blog'
            ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => ['id', 'parent_id', 'blog_id', 'username', 'comment', 'depth', 'replies']
            ]);

        $this->assertDatabaseHas('comments', [
            'blog_id'  => $this->blog->id,
            'username' => 'cjronxel',
            'comment'  => 'This is a test comment for a blog'
        ]);
    }

    /** @test */
    public function itShouldValdiateCommentIdFieldWhenReplyingABlogComment()
    {
        $firstCommemt = $this->postJson(route('blog.comment', [
                'blog' => $this->blog
            ]), [
                'blog_id'  => $this->blog->id,
                'username' => 'cjronxel',
                'comment'  => 'This is a test comment for a blog'
            ])
            ->assertJsonStructure([
                'data' => ['id', 'parent_id', 'blog_id', 'username', 'comment', 'depth', 'replies']
            ]);
    
        $firstCommemtData = $firstCommemt->json()['data'];

        $this->assertDatabaseHas('comments', [
            'blog_id'  => $this->blog->id,
            'username' => 'cjronxel',
            'comment'  => 'This is a test comment for a blog'
        ]);
            
        $this->postJson(route('blog.comment', [
                'blog' => $this->blog
            ]), [
                'blog_id'    => $this->blog->id,
                'comment_id' => null,
                'username'   => 'cjronxel',
                'comment'    => 'This is a test reply on a comment for a blog'
            ])
            ->assertStatus(422)
            ->assertSeeText('The comment id field is required.');
    }

    /** @test */
    public function userCanReplyACommentOnABlogPost()
    {
        $firstCommemt = $this->postJson(route('blog.comment', [
                'blog' => $this->blog
            ]), [
                'blog_id'  => $this->blog->id,
                'username' => 'cjronxel',
                'comment'  => 'This is a test comment for a blog'
            ])
            ->assertJsonStructure([
                'data' => ['id', 'parent_id', 'blog_id', 'username', 'comment', 'depth', 'replies']
            ]);
      
        $firstCommemtData = $firstCommemt->json()['data'];
            
        $this->postJson(route('blog.comment', [
                'blog' => $this->blog
            ]), [
                'blog_id'    => $this->blog->id,
                'comment_id' => $firstCommemtData['id'],
                'username'   => 'cjronxel',
                'comment'    => 'This is a test reply on a comment for a blog'
            ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => ['id', 'parent_id', 'blog_id', 'username', 'comment', 'depth', 'replies']
            ]);

        $this->assertDatabaseHas('comments', [
            'blog_id'  => $this->blog->id,
            'username' => 'cjronxel',
            'comment'  => 'This is a test comment for a blog'
        ]);
        
        $this->assertDatabaseHas('comments', [
            'blog_id'   => $this->blog->id,
            'parent_id' => $firstCommemtData['id'],
            'username'  => 'cjronxel',
            'comment'   => 'This is a test reply on a comment for a blog'
        ]);
    }
}