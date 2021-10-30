<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use App\Models\Blog;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        factory(Blog::class, 100)->create();
    }

    /** @test */
    public function userShouldSeeListOfBlogs()
    {
        $response = $this->getJson(route('blogs'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['title', 'body', 'comments', 'created_at']
                ]
            ]);

        $this->assertCount(100, $response->json()['data']);
    }
}
