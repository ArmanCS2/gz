<div>
    <h2 class="mb-4">ویرایش کاربر</h2>

    <form wire:submit.prevent="save">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">نام</label>
                    <input type="text" class="form-control" wire:model="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">شماره موبایل</label>
                    <input type="text" class="form-control" value="{{ $user->mobile }}" disabled>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" wire:model="is_admin" id="is_admin">
                        <label class="form-check-label" for="is_admin">مدیر</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" wire:model="is_verified" id="is_verified">
                        <label class="form-check-label" for="is_verified">تایید شده</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">ذخیره</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">انصراف</a>
            </div>
        </div>
    </form>
</div>











