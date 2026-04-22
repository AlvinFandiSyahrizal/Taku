{{-- resources/views/admin/products/_category_select.blade.php --}}
@php $selectedId = $selectedId ?? null; @endphp

<select name="category_id" class="form-input" style="appearance:none;-webkit-appearance:none;">
    <option value="">— Tanpa Kategori —</option>
    @foreach($categories as $cat)
        @if($cat->children && $cat->children->count() > 0)
            <optgroup label="{{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}">
                <option value="{{ $cat->id }}" {{ $selectedId == $cat->id ? 'selected' : '' }} style="font-style:italic;">
                    ({{ $cat->name }} — semua)
                </option>
                @foreach($cat->children as $child)
                    <option value="{{ $child->id }}" {{ $selectedId == $child->id ? 'selected' : '' }}>
                        &nbsp;&nbsp;{{ $child->icon ? $child->icon . ' ' : '' }}{{ $child->name }}
                    </option>
                @endforeach
            </optgroup>
        @else
            <option value="{{ $cat->id }}" {{ $selectedId == $cat->id ? 'selected' : '' }}>
                {{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}
            </option>
        @endif
    @endforeach
</select>