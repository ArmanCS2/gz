<?php

namespace App\Livewire\Admin\Rules;

use App\Models\SiteRule;
use Livewire\Component;

class Edit extends Component
{
    public $rule_id = null;
    public $title = '';
    public $content = '';
    public $is_active = true;

    public function mount($rule = 'new')
    {
        if ($rule !== 'new' && $rule) {
            $ruleModel = is_numeric($rule) ? SiteRule::findOrFail($rule) : $rule;
            $this->rule_id = $ruleModel->id;
            $this->title = $ruleModel->title;
            $this->content = $ruleModel->content;
            $this->is_active = $ruleModel->is_active;
        }
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
            // Refresh and reload values
            $rule->refresh();
            $this->title = $rule->title;
            $this->content = $rule->content;
            $this->is_active = $rule->is_active;
        } else {
            $rule = SiteRule::create([
                'title' => $this->title,
                'content' => $this->content,
                'is_active' => $this->is_active,
            ]);
            // Reset form for new entry
            $this->reset(['rule_id', 'title', 'content', 'is_active']);
            $this->is_active = true; // Default value
        }

        $this->dispatch('showToast', ['message' => 'قانون ذخیره شد.', 'type' => 'success']);
        
        // Only redirect if creating new rule
        if (!$this->rule_id) {
            return $this->redirect(route('admin.rules.index'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.admin.rules.edit')->layout('layouts.admin');
    }
}

