<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $showModal = false;
    public $category_id = null;
    public $name = '';
    public $description = '';
    public $icon = '';
    public $order = 0;
    public $is_active = true;

    public function openCreateModal()
    {
        $this->resetForm();
        $this->category_id = null;
        $this->showModal = true;
        $this->dispatch('openModal', ['modalId' => 'categoryModal']);
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $category = Category::findOrFail($id);
        $this->fillEditForm($category);
        $this->category_id = $category->id;
        $this->showModal = true;
        $this->dispatch('openModal', ['modalId' => 'categoryModal']);
    }

    public function openModal($id = null)
    {
        if ($id) {
            $this->openEditModal($id);
        } else {
            $this->openCreateModal();
        }
    }

    protected function resetForm()
    {
        // Reset all form-related properties to their default values
        $this->reset(['category_id', 'name', 'description', 'icon', 'order', 'is_active']);
        
        // Set create defaults explicitly
        $this->category_id = null;
        $this->name = '';
        $this->description = '';
        $this->icon = '';
        $this->order = 0;
        $this->is_active = true;
        
        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected function fillEditForm($category)
    {
        // Explicitly assign each form field from the model
        $this->name = $category->name;
        $this->description = $category->description ?? '';
        $this->icon = $category->icon ?? '';
        $this->order = $category->order ?? 0;
        $this->is_active = $category->is_active;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('closeModal', ['modalId' => 'categoryModal']);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($this->category_id) {
            $category = Category::findOrFail($this->category_id);
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
                'icon' => $this->icon,
                'order' => $this->order ?? 0,
                'is_active' => $this->is_active,
            ]);
            $message = 'دسته‌بندی به‌روزرسانی شد.';
        } else {
            Category::create([
                'name' => $this->name,
                'description' => $this->description,
                'icon' => $this->icon,
                'order' => $this->order ?? 0,
                'is_active' => $this->is_active,
            ]);
            $message = 'دسته‌بندی ایجاد شد.';
        }

        // Reset form BEFORE closing modal to ensure clean state
        $this->resetForm();
        $this->showModal = false;
        
        // Dispatch events
        $this->dispatch('closeModal', ['modalId' => 'categoryModal']);
        $this->dispatch('showToast', ['message' => $message, 'type' => 'success']);
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has ads
        if ($category->ads()->count() > 0) {
            $this->dispatch('showToast', ['message' => 'این دسته‌بندی دارای آگهی است و نمی‌توان آن را حذف کرد.', 'type' => 'error']);
            return;
        }
        
        $category->delete();
        $this->dispatch('showToast', ['message' => 'دسته‌بندی حذف شد.', 'type' => 'success']);
    }

    public function render()
    {
        $categories = Category::orderBy('order')->orderBy('name')->paginate(10);
        $categories->setPath('/admin/categories');
        return view('livewire.admin.categories.index', [
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}

