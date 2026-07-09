<x-layouts.admin>
    <h1 class="text-2xl font-bold mb-6">New Blog Post</h1>

    @include('admin.blogs._form', [
    'action' => route('admin.blogs.store'),
    'blog' => null,
    ])
</x-layouts.admin>