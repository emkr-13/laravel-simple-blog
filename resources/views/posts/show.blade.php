<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-2xl font-semibold">{{ $post->title }}</h2>
                                <div class="mt-2 text-sm text-gray-500">
                                    Posted by {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($post->status === 'published')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Published</span>
                                @elseif($post->status === 'draft')
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Draft</span>
                                @else
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Scheduled for {{ $post->published_at->format('M d, Y H:i') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="prose max-w-none">
                        {{ $post->content }}
                    </div>

                    @if(Auth::id() === $post->user_id)
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('posts.edit', $post) }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Edit</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            <a href="#" class="inline-flex gap-2 items-center text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="size-4 fill-blue-500"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M34.5 239L228.9 44.7c9.4-9.4 24.6-9.4 33.9 0l22.7 22.7c9.4 9.4 9.4 24.5 0 33.9L131.5 256l154 154.8c9.3 9.4 9.3 24.5 0 33.9l-22.7 22.7c-9.4 9.4-24.6 9.4-33.9 0L34.5 273c-9.4-9.4-9.4-24.6 0-33.9z"/></svg>{{ __('Back to All Posts') }}
            </a>
        </div>
    </div>
</x-app-layout>
