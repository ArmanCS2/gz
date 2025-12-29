<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;

    public $categoryId = null;
    public $tagId = null;
    public $authorId = null;
    public $perPage = 12;

    public function mount($categoryId = null, $tagId = null, $authorId = null)
    {
        $this->categoryId = $categoryId;
        $this->tagId = $tagId;
        $this->authorId = $authorId;
    }

    public function render()
    {
        $query = Post::published()
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc');

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->tagId) {
            $query->whereHas('tags', function ($q) {
                $q->where('tags.id', $this->tagId);
            });
        }

        if ($this->authorId) {
            $query->where('user_id', $this->authorId);
        }

        $posts = $query->paginate($this->perPage);

        return view('livewire.blog.post-list', [
            'posts' => $posts,
        ]);
    }
}






