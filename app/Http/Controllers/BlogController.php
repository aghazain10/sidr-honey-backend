<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    // GET /api/blogs
    public function index(Request $request)
    {
        $limit = $request->query('limit', 9);

        $blogs = Blog::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data'    => $blogs,
        ]);
    }

    // GET /api/blogs/{slug}
    public function show(string $slug)
    {
        $blog = Blog::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('slug', $slug)
            ->first();

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $blog,
        ]);
    }
}
