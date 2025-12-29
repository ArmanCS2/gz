<?php

namespace App\Livewire\Admin;

use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\HandlesFileUploads;

class Settings extends Component
{
    use WithFileUploads, HandlesFileUploads;
    
    public $site_name = '';
    public $logo = null;
    public $logoPreview = null;
    public $ad_daily_price = '';
    public $auction_daily_price = '';
    public $zarinpal_merchant_id = '';
    public $melipayamak_username = '';
    public $melipayamak_password = '';
    public $melipayamak_from_number = '';
    public $ad_auto_approve = false;
    public $active_ads = 0;
    public $total_members = 0;
    public $active_users = 0;
    public $successful_deals = 0;
    public $satisfaction_percent = 100;
    public $rating = 4.9;

    public $showModal = false;

    public function mount()
    {
        $settings = SiteSetting::getSettings();
        $this->loadSettings($settings);
    }

    protected function loadSettings($settings)
    {
        $this->site_name = $settings->site_name;
        $this->logoPreview = $settings->logo ? asset($settings->logo) : null;
        $this->ad_daily_price = $settings->ad_daily_price;
        $this->auction_daily_price = $settings->auction_daily_price;
        $this->zarinpal_merchant_id = $settings->zarinpal_merchant_id ?? '';
        $this->melipayamak_username = $settings->melipayamak_username ?? '';
        $this->melipayamak_password = $settings->melipayamak_password ?? '';
        $this->melipayamak_from_number = $settings->melipayamak_from_number ?? '';
        $this->ad_auto_approve = (bool) $settings->ad_auto_approve;
        $this->active_ads = $settings->active_ads ?? 0;
        $this->total_members = $settings->total_members ?? 0;
        $this->active_users = $settings->active_users ?? 0;
        $this->successful_deals = $settings->successful_deals ?? 0;
        $this->satisfaction_percent = $settings->satisfaction_percent ?? 100;
        $this->rating = $settings->rating ?? 4.9;
    }

    public function openModal()
    {
        $settings = SiteSetting::getSettings();
        $this->loadSettings($settings);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetValidation();
        $this->logo = null;
    }
    
    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'image|max:2048', // 2MB max
        ]);
        
        $this->logoPreview = $this->logo->temporaryUrl();
    }

    public function save()
    {
        $this->validate([
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'ad_daily_price' => 'required|numeric|min:0',
            'auction_daily_price' => 'required|numeric|min:0',
            'zarinpal_merchant_id' => 'nullable|string',
            'melipayamak_username' => 'nullable|string|max:255',
            'melipayamak_password' => 'nullable|string|max:255',
            'melipayamak_from_number' => 'nullable|string|max:50',
            'ad_auto_approve' => 'boolean',
            'active_ads' => 'required|integer|min:0',
            'total_members' => 'required|integer|min:0',
            'active_users' => 'required|integer|min:0',
            'successful_deals' => 'required|integer|min:0',
            'satisfaction_percent' => 'required|integer|min:0|max:100',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        $settings = SiteSetting::getSettings();
        
        // اطمینان از اینکه ad_auto_approve به صورت boolean ذخیره شود
        // اگر checkbox غیرفعال باشد، مقدار false خواهد بود (نه null یا 0)
        $adAutoApprove = filter_var($this->ad_auto_approve, FILTER_VALIDATE_BOOLEAN);
        
        $data = [
            'site_name' => $this->site_name,
            'ad_daily_price' => $this->ad_daily_price,
            'auction_daily_price' => $this->auction_daily_price,
            'zarinpal_merchant_id' => $this->zarinpal_merchant_id,
            'melipayamak_username' => $this->melipayamak_username,
            'melipayamak_from_number' => $this->melipayamak_from_number,
            'ad_auto_approve' => $adAutoApprove, // boolean صریح
            'active_ads' => $this->active_ads,
            'total_members' => $this->total_members,
            'active_users' => $this->active_users,
            'successful_deals' => $this->successful_deals,
            'satisfaction_percent' => $this->satisfaction_percent,
            'rating' => (int)$this->rating, // اطمینان از اینکه به صورت float ذخیره شود
        ];
        
        // فقط اگر رمز عبور جدید وارد شده باشد، آن را ذخیره کن
        if (!empty($this->melipayamak_password)) {
            $data['melipayamak_password'] = $this->melipayamak_password;
        }
        
        // اگر لوگو جدید آپلود شده باشد
        if ($this->logo) {
            // حذف لوگوی قبلی
            if ($settings->logo) {
                $this->deleteFromPublic($settings->logo);
            }
            
            // ذخیره لوگوی جدید
            $data['logo'] = $this->uploadToPublic($this->logo, 'site');
        }
        
        $settings->update($data);
        
        // Refresh settings to ensure we have the latest data
        $settings->refresh();
        
        // Reload settings to update component properties
        $this->loadSettings($settings);

        $this->dispatch('closeModal');
        $this->closeModal();
        $this->dispatch('showToast', ['message' => 'تنظیمات ذخیره شد.', 'type' => 'success']);
    }

    public function render()
    {
        $settings = SiteSetting::getSettings();
        return view('livewire.admin.settings', [
            'settings' => $settings,
        ])->layout('layouts.admin');
    }
}

