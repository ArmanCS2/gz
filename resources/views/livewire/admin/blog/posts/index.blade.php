<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #ffffff;">
            <i class="bi bi-file-text me-2" style="color: #00f0ff;"></i>
            مدیریت مقالات
        </h2>
        <a href="{{ route('admin.blog.posts.create') }}" class="btn btn-modern">
            <i class="bi bi-plus-circle me-2"></i> ایجاد مقاله جدید
        </a>
    </div>

    <!-- Filters -->
    <div class="glass-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" wire:model.live.debounce.500ms="search" class="form-control modern-input" placeholder="جستجو در مقالات...">
            </div>
            <div class="col-md-4">
                <select wire:model.live="statusFilter" class="form-select modern-input">
                    <option value="all">همه وضعیت‌ها</option>
                    <option value="draft">پیش‌نویس</option>
                    <option value="published">منتشر شده</option>
                    <option value="scheduled">زمان‌بندی شده</option>
                </select>
            </div>
            <div class="col-md-4">
                <select wire:model.live="categoryFilter" class="form-select modern-input">
                    <option value="all">همه دسته‌بندی‌ها</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="glass-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>عنوان</th>
                            <th>دسته‌بندی</th>
                            <th>نویسنده</th>
                            <th>وضعیت</th>
                            <th>بازدید</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>
                                    <a href="{{ route('blog.post', $post->slug) }}" class="text-white text-decoration-none" target="_blank">
                                        {{ Str::limit($post->title, 40) }}
                                    </a>
                                    @if($post->is_featured)
                                    <span class="badge ms-2" style="background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3);">
                                        <i class="bi bi-star-fill"></i> ویژه
                                    </span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $post->category->name ?? '-' }}</td>
                                <td class="text-muted">{{ $post->author->name }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $post->status === 'published' ? 'rgba(57, 255, 20, 0.2)' : ($post->status === 'draft' ? 'rgba(255, 170, 0, 0.2)' : 'rgba(0, 240, 255, 0.2)') }}; color: {{ $post->status === 'published' ? '#39ff14' : ($post->status === 'draft' ? '#ffaa00' : '#00f0ff') }}; border: 1px solid {{ $post->status === 'published' ? 'rgba(57, 255, 20, 0.3)' : ($post->status === 'draft' ? 'rgba(255, 170, 0, 0.3)' : 'rgba(0, 240, 255, 0.3)') }};">
                                        {{ $post->status === 'published' ? 'منتشر شده' : ($post->status === 'draft' ? 'پیش‌نویس' : 'زمان‌بندی شده') }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ number_format($post->views_count) }}</td>
                                <td class="text-muted">{{ $post->published_at ? $post->published_at->format('Y/m/d') : '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.blog.posts.edit', $post->id) }}" 
                                           class="btn btn-modern btn-sm" 
                                           style="background: rgba(0, 240, 255, 0.2); box-shadow: none; padding: 4px 12px;">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button wire:click="delete({{ $post->id }})" 
                                                wire:confirm="آیا از حذف این مقاله مطمئن هستید؟"
                                                class="btn btn-modern btn-sm" 
                                                style="background: rgba(255, 0, 110, 0.2); box-shadow: none; padding: 4px 12px;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mt-3 mb-0">مقاله‌ای یافت نشد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($posts->hasPages())
            <div class="mt-3 p-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="d-flex justify-content-center">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


