<?php

namespace App\Livewire\Admin\Rules;

use App\Models\SiteRule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $showModal = false;
    public $rule_id = null;
    public $title = '';
    public $content = '';
    public $is_active = true;

    public function openCreateModal()
    {
        $this->resetForm();
        $this->rule_id = null;
        $this->showModal = true;
        $this->dispatch('openModal', ['modalId' => 'ruleModal']);
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $rule = SiteRule::findOrFail($id);
        $this->fillEditForm($rule);
        $this->rule_id = $rule->id;
        $this->showModal = true;
        $this->dispatch('openModal', ['modalId' => 'ruleModal']);
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
        $this->reset(['rule_id', 'title', 'content', 'is_active']);
        
        // Set create defaults explicitly
        $this->rule_id = null;
        $this->title = '';
        $this->content = '';
        $this->is_active = true;
        
        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected function fillEditForm($rule)
    {
        // Explicitly assign each form field from the model
        $this->title = $rule->title;
        $this->content = $rule->content;
        $this->is_active = $rule->is_active;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('closeModal', ['modalId' => 'ruleModal']);
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        if ($this->rule_id) {
            $rule = SiteRule::findOrFail($this->rule_id);
            $rule->update([
                'title' => $this->title,
                'content' => $this->content,
                'is_active' => $this->is_active,
            ]);
            $message = 'قانون به‌روزرسانی شد.';
        } else {
            SiteRule::create([
                'title' => $this->title,
                'content' => $this->content,
                'is_active' => $this->is_active,
            ]);
            $message = 'قانون ایجاد شد.';
        }

        // Reset form BEFORE closing modal to ensure clean state
        $this->resetForm();
        $this->showModal = false;
        
        // Dispatch events
        $this->dispatch('closeModal', ['modalId' => 'ruleModal']);
        $this->dispatch('showToast', ['message' => $message, 'type' => 'success']);
    }

    public function delete($id)
    {
        SiteRule::findOrFail($id)->delete();
        $this->dispatch('showToast', ['message' => 'قانون حذف شد.', 'type' => 'success']);
    }

    public function render()
    {
        $rules = SiteRule::latest()->paginate(10);
        $rules->setPath('/admin/rules');
        return view('livewire.admin.rules.index', [
            'rules' => $rules,
        ])->layout('layouts.admin');
    }
}

