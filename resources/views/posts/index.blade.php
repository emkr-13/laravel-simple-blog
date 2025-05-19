<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">All Posts</h2>
                        @auth
                            <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create New Post
                            </a>
                        @endauth
                    </div>

                    @if($posts->isEmpty())
                        <p class="text-gray-500 text-center">No posts available.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($posts as $post)
                                <div class="border-b pb-4 last:border-b-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('posts.show', $post) }}" class="text-xl font-semibold hover:text-blue-600">
                                                {{ $post->title }}
                                            </a>
                                            <p class="text-gray-600 mt-2">{{ Str::limit($post->content, 200) }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Published</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between text-sm">
                                        <span class="text-gray-500">
                                            {{ $post->created_at->format('M d, Y') }}
                                            by {{ $post->user->name }}
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

                        <div class="mt-6">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
