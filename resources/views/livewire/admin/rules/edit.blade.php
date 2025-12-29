<div>
    <h2 class="mb-4">{{ $rule_id ? 'ویرایش' : 'ایجاد' }} قانون</h2>

    <form wire:submit.prevent="save">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">عنوان</label>
                    <input type="text" class="form-control" wire:model="title">
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">محتوا</label>
                    <textarea class="form-control" rows="10" wire:model="content"></textarea>
                    @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" wire:model="is_active" id="is_active">
                        <label class="form-check-label" for="is_active">فعال</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">ذخیره</button>
                <a href="{{ route('admin.rules.index') }}" class="btn btn-secondary">انصراف</a>
            </div>
        </div>
    </form>
</div>











