<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // For authenticated users, show their posts
            $posts = Auth::user()->posts()
                ->latest()
                ->get();
        } else {
            // For guests, show only published posts
            $posts = Post::published()
                ->latest()
                ->get();
        }

        return view('home', compact('posts'));
    }
}
