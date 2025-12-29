<?php

namespace App\Livewire\Admin\Blog\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $categoryFilter = 'all';

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        
        $this->dispatch('showToast', ['message' => 'مقاله با موفقیت حذف شد.', 'type' => 'success']);
    }

    public function render()
    {
        $query = Post::with(['category', 'author'])
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->categoryFilter !== 'all') {
            $query->where('category_id', $this->categoryFilter);
        }

        $posts = $query->paginate(15);
        $posts->setPath('/admin/blog/posts');

        return view('livewire.admin.blog.posts.index', [
            'posts' => $posts,
            'categories' => \App\Models\Category::all(),
        ])->layout('layouts.admin');
    }
}


