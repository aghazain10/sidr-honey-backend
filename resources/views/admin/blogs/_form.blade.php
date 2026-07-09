<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="max-w-3xl">
    @csrf
    @if ($blog)
    @method('PUT')
    @endif

    @if ($errors->any())
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        <ul class="list-disc ml-4">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <label class="block mb-2 font-medium text-gray-700">Title</label>
    <input type="text" name="title" value="{{ old('title', $blog->title ?? '') }}"
        class="w-full border rounded-lg px-4 py-2 mb-4" required>

    <label class="block mb-2 font-medium text-gray-700">Short Excerpt</label>
    <textarea name="excerpt" rows="2"
        class="w-full border rounded-lg px-4 py-2 mb-1"
        placeholder="A short teaser shown on the homepage and blog listing">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
    <p class="text-sm text-gray-500 mb-4">Max 500 characters. This is what shows on the blog cards, not the full post.</p>

    <label class="block mb-2 font-medium text-gray-700">Cover Image</label>
    @if ($blog && $blog->image)
    <img src="{{ $blog->image_url }}" class="w-40 h-28 object-cover rounded mb-2">
    <p class="text-sm text-gray-500 mb-2">Uploading a new image will replace this one.</p>
    @endif
    <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-4 py-2 mb-4">

    <label class="block mb-2 font-medium text-gray-700">Content</label>
    <!-- Quill editor mounts here -->
    <div id="quill-editor"
        class="bg-white"
        style="height: 300px;"
        data-upload-url="{{ route('admin.blogs.upload-image') }}">{!! old('content', $blog->content ?? '') !!}</div>
    <!-- Hidden input that actually gets submitted with the form -->
    <input type="hidden" name="content" id="content-input">
    <div class="mb-14"></div> {{-- spacing to clear the editor toolbar --}}

    <label class="inline-flex items-center mb-6">
        <input type="checkbox" name="published_now" value="1"
            {{ old('published_now', $blog?->published_at) ? 'checked' : '' }}
            class="mr-2">
        <span class="text-gray-700">Publish this post now</span>
    </label>

    <div class="flex gap-4">
        <button type="submit" class="bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700">
            {{ $blog ? 'Update Post' : 'Create Post' }}
        </button>
        <a href="{{ route('admin.blogs.index') }}" class="px-6 py-2 border rounded-lg">Cancel</a>
    </div>
</form>

<!-- Quill CSS + JS via CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editorEl = document.getElementById('quill-editor');
        const uploadUrl = editorEl.dataset.uploadUrl;

        const quill = new Quill(editorEl, {
            theme: 'snow',
            placeholder: 'Write your blog post here...',
            modules: {
                toolbar: {
                    container: [
                        [{
                            header: [2, 3, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }],
                        ['link', 'image'],
                        ['clean'],
                    ],
                    handlers: {
                        image: imageHandler,
                    },
                },
            },
        });

        function imageHandler() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                if (!file) return;

                const range = quill.getSelection(true);

                // Show a temporary placeholder while uploading
                quill.insertText(range.index, 'Uploading image...', {
                    italic: true
                });

                const formData = new FormData();
                formData.append('image', file);

                try {
                    const response = await fetch(uploadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    // Remove the placeholder text
                    quill.deleteText(range.index, 'Uploading image...'.length);

                    // Insert the real image using its uploaded URL
                    quill.insertEmbed(range.index, 'image', data.url);
                    quill.setSelection(range.index + 1);
                } catch (error) {
                    quill.deleteText(range.index, 'Uploading image...'.length);
                    alert('Image upload failed. Please try again.');
                    console.error(error);
                }
            };
        }

        const form = quill.root.closest('form');
        const hiddenInput = document.getElementById('content-input');

        form.addEventListener('submit', function() {
            hiddenInput.value = quill.root.innerHTML;
        });
    });
</script>