<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::where('email', 'test@example.com')->first();

        if (!$user) {
            $user = \App\Models\User::factory()->create([
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Published post
        \App\Models\Post::create([
            'title' => 'Published Post Example',
            'content' => 'This is a published post content.',
            'status' => 'published',
            'published_at' => now(),
            'user_id' => $user->id,
        ]);

        // Draft post
        \App\Models\Post::create([
            'title' => 'Draft Post Example',
            'content' => 'This is a draft post content.',
            'status' => 'draft',
            'user_id' => $user->id,
        ]);

        // Scheduled post
        \App\Models\Post::create([
            'title' => 'Scheduled Post Example',
            'content' => 'This is a scheduled post content.',
            'status' => 'scheduled',
            'published_at' => now()->addDays(7),
            'user_id' => $user->id,
        ]);
    }
}
