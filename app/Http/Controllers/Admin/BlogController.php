<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'excerpt'       => 'nullable|string|max:500',
            'content'       => 'required|string',
            'image'         => 'nullable|image|max:4096', // 4MB max
            'published_now' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $image = Image::read($request->file('image'))
                ->scaleDown(width: 1200)
                ->toJpeg(quality: 80);

            $filename = 'blogs/' . uniqid() . '.jpg';
            Storage::disk('public')->put($filename, (string) $image);
            $validated['image'] = $filename;
        }

        $validated['published_at'] = $request->boolean('published_now') ? now() : null;

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'excerpt'       => 'nullable|string|max:500',
            'content'       => 'required|string',
            'image'         => 'nullable|image|max:4096',
            'published_now' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        $validated['published_at'] = $request->boolean('published_now')
            ? ($blog->published_at ?? now())
            : null;

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted.');
    }
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
        ]);

        $path = $request->file('image')->store('blogs/content', 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }
}
