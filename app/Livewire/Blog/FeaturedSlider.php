<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;

class FeaturedSlider extends Component
{
    public $limit = 5;

    public function render()
    {
        $posts = Post::published()
            ->featured()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit($this->limit)
            ->get();

        return view('livewire.blog.featured-slider', [
            'posts' => $posts,
        ]);
    }
}






