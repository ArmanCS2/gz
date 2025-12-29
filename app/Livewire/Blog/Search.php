<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $query = '';
    public $perPage = 12;

    protected $queryString = ['query' => ['except' => '']];

    public function updatingQuery()
    {
        $this->resetPage();
    }

    public function render()
    {
        $posts = collect();

        if (strlen($this->query) >= 2) {
            $posts = Post::published()
                ->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->query . '%')
                      ->orWhere('excerpt', 'like', '%' . $this->query . '%')
                      ->orWhere('content', 'like', '%' . $this->query . '%');
                })
                ->with(['category', 'author', 'tags'])
                ->orderBy('published_at', 'desc')
                ->paginate($this->perPage);
            
            $posts->setPath('/blog/search');
        }

        // SEO Rules: Search result pages = NOINDEX, FOLLOW
        $robotsMeta = 'noindex, follow';
        $canonicalUrl = route('blog.search');

        return view('livewire.blog.search', [
            'posts' => $posts,
        ])->layout('layouts.app', [
            'title' => !empty($this->query) ? 'جستجو: ' . $this->query . ' | بلاگ' : 'جستجو در بلاگ',
            'description' => 'نتایج جستجو در بلاگ',
            'canonical' => $canonicalUrl,
            'robots' => $robotsMeta,
        ]);
    }
}


