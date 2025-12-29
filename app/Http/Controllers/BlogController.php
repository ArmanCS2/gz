<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $featuredPosts = Post::published()
            ->featured()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        $latestPosts = Post::published()
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        $latestPosts->setPath('/blog');

        $categories = Category::where('is_active', true)
            ->withCount('publishedPosts')
            ->orderBy('order')
            ->get();

        $trendingPosts = Post::published()
            ->with(['category', 'author'])
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        return view('blog.index', [
            'featuredPosts' => $featuredPosts,
            'latestPosts' => $latestPosts,
            'categories' => $categories,
            'trendingPosts' => $trendingPosts,
        ]);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['category', 'author', 'tags', 'approvedComments.user'])
            ->firstOrFail();

        // Only show published posts to non-admins
        if (!$post->isPublished() && !auth()->user()?->is_admin) {
            abort(404);
        }

        // Track view (once per visitor)
        $this->trackView($post);

        // Get related posts
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->where('category_id', $post->category_id)
                      ->orWhereHas('tags', function ($q) use ($post) {
                          $q->whereIn('tags.id', $post->tags->pluck('id'));
                      });
            })
            ->with(['category', 'author'])
            ->limit(6)
            ->get();

        return view('blog.post', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        $posts->setPath('/blog/category/' . $category->slug);

        return view('blog.category', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        $posts->setPath('/blog/tag/' . $tag->slug);

        return view('blog.tag', [
            'tag' => $tag,
            'posts' => $posts,
        ]);
    }

    public function author($id)
    {
        $author = User::findOrFail($id);

        $posts = Post::published()
            ->where('user_id', $author->id)
            ->with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        $posts->setPath('/blog/author/' . $author->id);

        return view('blog.author', [
            'author' => $author,
            'posts' => $posts,
        ]);
    }

    private function trackView(Post $post)
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        $today = now()->startOfDay();

        // Check if this IP already viewed this post today
        $existingView = \App\Models\PostView::where('post_id', $post->id)
            ->where('ip_address', $ip)
            ->whereDate('viewed_at', $today)
            ->first();

        if (!$existingView) {
            \App\Models\PostView::create([
                'post_id' => $post->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'viewed_at' => now(),
            ]);

            $post->incrementViews();
        }
    }
}


