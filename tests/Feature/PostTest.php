<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_published_posts(): void
    {
        $post = Post::factory()->published()->create();

        $response = $this->get('/posts');

        $response->assertStatus(200)
            ->assertSee($post->title)
            ->assertSee('Published');
    }

    public function test_guest_cannot_view_draft_posts(): void
    {
        $post = Post::factory()->draft()->create();

        $response = $this->get('/posts/' . $post->id);

        $response->assertStatus(404);
    }

    public function test_guest_cannot_create_post(): void
    {
        $response = $this->get('/posts/create');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/posts', [
                'title' => 'Test Post',
                'content' => 'Test Content',
                'status' => 'published',
            ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_edit_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->put('/posts/' . $post->id, [
                'title' => 'Updated Title',
                'content' => 'Updated Content',
                'status' => 'published',
            ]);

        $response->assertRedirect('/posts/' . $post->id);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_user_cannot_edit_others_post(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)
            ->put('/posts/' . $post->id, [
                'title' => 'Updated Title',
                'content' => 'Updated Content',
                'status' => 'published',
            ]);

        $response->assertStatus(403);
    }

    public function test_post_requires_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/posts', [
                'content' => 'Test Content',
                'status' => 'published',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_post_title_cannot_exceed_60_characters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/posts', [
                'title' => str_repeat('a', 61),
                'content' => 'Test Content',
                'status' => 'published',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_scheduled_post_requires_published_at(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/posts', [
                'title' => 'Test Post',
                'content' => 'Test Content',
                'status' => 'scheduled',
            ]);

        $response->assertSessionHasErrors('published_at');
    }
}
