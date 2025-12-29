<?php

namespace App\Livewire\Admin\Blog\Posts;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\HandlesFileUploads;
use Illuminate\Support\Str;
use Hekmatinasser\Verta\Verta;

class Edit extends Component
{
    use WithFileUploads, HandlesFileUploads;

    public $post;
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $category_id = '';
    public $banner_image;
    public $banner_image_preview = null;
    public $is_featured = false;
    public $status = 'draft';
    public $published_date = '';
    public $published_time = '';
    public $seo_title = '';
    public $seo_description = '';
    public $seo_keywords = '';
    public $selected_tags = [];

    public function mount($id)
    {
        $this->post = Post::findOrFail($id);
        $this->title = $this->post->title;
        $this->slug = $this->post->slug;
        $this->excerpt = $this->post->excerpt;
        $this->content = $this->post->content;
        $this->category_id = $this->post->category_id;
        $this->banner_image_preview = $this->post->banner_image ? asset($this->post->banner_image) : null;
        $this->is_featured = $this->post->is_featured;
        $this->status = $this->post->status;
        // Convert Gregorian datetime to Jalali date and time
        if ($this->post->published_at) {
            try {
                $verta = Verta::instance($this->post->published_at);
                $this->published_date = $verta->format('Y/m/d');
                $this->published_time = $this->post->published_at->format('H:i');
            } catch (\Exception $e) {
                $this->published_date = '';
                $this->published_time = '';
            }
        } else {
            $this->published_date = '';
            $this->published_time = '';
        }
        $this->seo_title = $this->post->seo_title;
        $this->seo_description = $this->post->seo_description;
        $this->seo_keywords = $this->post->seo_keywords;
        $this->selected_tags = $this->post->tags->pluck('id')->toArray();
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $this->post->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'banner_image' => 'nullable|image|max:5120',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,scheduled',
            'published_date' => 'nullable|string',
            'published_time' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
        ];
    }

    public function updatedTitle()
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title, '-', 'fa');
        }
    }

    public function updatedBannerImage()
    {
        if ($this->banner_image) {
            $this->banner_image_preview = $this->banner_image->temporaryUrl();
        }
    }

    public function update()
    {
        $this->validate();

        // Convert Jalali date to Gregorian datetime
        $publishedAt = null;
        if (!empty($this->published_date)) {
            try {
                // Parse Jalali date (format: YYYY/MM/DD)
                $parts = explode('/', $this->published_date);
                if (count($parts) === 3) {
                    $year = (int)$parts[0];
                    $month = (int)$parts[1];
                    $day = (int)$parts[2];
                    
                    // Parse time (format: HH:MM)
                    $timeParts = explode(':', $this->published_time ?: '00:00');
                    $hour = isset($timeParts[0]) ? (int)$timeParts[0] : 0;
                    $minute = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
                    
                    // Convert Jalali to Gregorian
                    $verta = Verta::createJalali($year, $month, $day, $hour, $minute, 0);
                    $publishedAt = $verta->datetime();
                }
            } catch (\Exception $e) {
                // If conversion fails, use current time if status is published
                if ($this->status === 'published') {
                    $publishedAt = now();
                }
            }
        } elseif ($this->status === 'published') {
            $publishedAt = now();
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'category_id' => $this->category_id ?: null,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'published_at' => $publishedAt,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $this->seo_keywords,
        ];

        if ($this->banner_image) {
            // Delete old image if exists
            if ($this->post->banner_image) {
                $this->deleteFromPublic($this->post->banner_image);
            }
            $data['banner_image'] = $this->uploadToPublic($this->banner_image, 'blog');
        }

        $this->post->update($data);
        $this->post->tags()->sync($this->selected_tags);

        $this->dispatch('showToast', ['message' => 'مقاله با موفقیت به‌روزرسانی شد.', 'type' => 'success']);
        return redirect()->route('admin.blog.posts.index');
    }

    public function render()
    {
        return view('livewire.admin.blog.posts.edit', [
            'categories' => Category::where('is_active', true)->get(),
            'tags' => Tag::all(),
        ])->layout('layouts.admin');
    }
}


