<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @auth
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold">Your Posts</h2>
                            <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create New Post
                            </a>
                        </div>
                    @endauth

                    @if($posts->isEmpty())
                        <p class="text-gray-500 text-center">
                            @auth
                                You haven't created any posts yet.
                            @else
                                No posts available.
                            @endauth
                        </p>
                    @else
                        <div class="space-y-6">
                            @foreach($posts as $post)
                                <div class="border-b pb-4 last:border-b-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-xl font-semibold">{{ $post->title }}</h3>
                                            <p class="text-gray-600 mt-2">{{ Str::limit($post->content, 200) }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($post->status === 'published')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Published</span>
                                            @elseif($post->status === 'draft')
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Draft</span>
                                            @else
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Scheduled</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between text-sm">
                                        <span class="text-gray-500">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </span>
                                        @auth
                                            @if($post->user_id === auth()->id())
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('posts.edit', $post) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
