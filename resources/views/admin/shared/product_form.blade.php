@php
    $isEdit = isset($model);
    $isNightCombo = request()->routeIs('admin.night-combos.*');
@endphp

<form
    action="{{ $isEdit
        ? ($isNightCombo
            ? route('admin.night-combos.update', $model['id'])
            : route('admin.products.update', $model['id']))
        : ($isNightCombo
            ? route('admin.night-combos.store')
            : route('admin.products.store'))
    }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $model['name'] ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Giá</label>
        <input type="number" name="price" class="form-control" value="{{ old('price', $model['price'] ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Danh mục</label>
        <select name="category_id" class="form-control" required>
            <option value="">-- Chọn danh mục --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat['id'] }}"
                    {{ old('category_id', $model['category_id'] ?? '') == $cat['id'] ? 'selected' : '' }}>
                    {{ $cat['name'] }}
                </option>
            @endforeach
        </select>
    </div>

    @if($isNightCombo)
        <div class="mb-3">
            <label class="form-label">Số lượng (stock)</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $model['stock'] ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Thời lượng (giờ)</label>
            <input type="number" name="duration_hours" class="form-control" value="{{ old('duration_hours', $model['duration_hours'] ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Giờ bắt đầu</label>
            <input type="number" name="start_hour" class="form-control" value="{{ old('start_hour', $model['start_hour'] ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Giờ kết thúc</label>
            <input type="number" name="end_hour" class="form-control" value="{{ old('end_hour', $model['end_hour'] ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Metadata (JSON)</label>
            <textarea name="metadata" class="form-control">{{ old('metadata', isset($model['metadata']) ? json_encode($model['metadata']) : '') }}</textarea>
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Ảnh đại diện</label>
        <input type="file" name="image" class="form-control">
        @if(isset($model['image_url']))
            <div class="mt-2"><img src="{{ $model['image_url'] }}" alt="Preview" width="150"></div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">
        {{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}
    </button>
    <a href="{{ $isNightCombo ? route('admin.night-combos.index') : route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
</form>
