<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_home_page_shows_login_register_for_guests(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Log in')
            ->assertSee('Register');
    }

    public function test_home_page_shows_published_posts_for_guests(): void
    {
        $publishedPost = Post::factory()->published()->create();
        $draftPost = Post::factory()->draft()->create();
        $scheduledPost = Post::factory()->scheduled()->create();

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee($publishedPost->title)
            ->assertDontSee($draftPost->title)
            ->assertDontSee($scheduledPost->title);
    }

    public function test_home_page_shows_all_own_posts_for_authenticated_users(): void
    {
        $user = User::factory()->create();
        $ownPublished = Post::factory()->published()->create(['user_id' => $user->id]);
        $ownDraft = Post::factory()->draft()->create(['user_id' => $user->id]);
        $ownScheduled = Post::factory()->scheduled()->create(['user_id' => $user->id]);
        
        $otherUser = User::factory()->create();
        $otherPublished = Post::factory()->published()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200)
            ->assertSee($ownPublished->title)
            ->assertSee($ownDraft->title)
            ->assertSee($ownScheduled->title)
            ->assertDontSee($otherPublished->title);
    }

    public function test_home_page_shows_create_post_button_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200)
            ->assertSee('Create New Post');
    }
}
