<x-layouts.admin>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Blog Posts</h1>
        <a href="{{ route('admin.blogs.create') }}"
            class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700">
            + New Post
        </a>
    </div>

    @if (session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="py-2 px-4">Image</th>
                <th class="py-2 px-4">Title</th>
                <th class="py-2 px-4">Status</th>
                <th class="py-2 px-4">Created</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($blogs as $blog)
            <tr class="border-t">
                <td class="py-2 px-4">
                    @if ($blog->image)
                    <img src="{{ $blog->image_url }}" class="w-16 h-12 object-cover rounded">
                    @else
                    <span class="text-gray-400 text-sm">No image</span>
                    @endif
                </td>
                <td class="py-2 px-4 font-medium text-gray-800">{{ $blog->title }}</td>
                <td class="py-2 px-4">
                    @if ($blog->published_at)
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Published</span>
                    @else
                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">Draft</span>
                    @endif
                </td>
                <td class="py-2 px-4 text-gray-600 text-sm">{{ $blog->created_at->format('d M Y') }}</td>
                <td class="py-2 px-4 space-x-2">
                    <a href="{{ route('admin.blogs.edit', $blog) }}"
                        class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST"
                        class="inline"
                        onsubmit="return confirm('Delete this post? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-6 px-4 text-center text-gray-500">
                    No blog posts yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $blogs->links() }}
    </div>
</x-layouts.admin>