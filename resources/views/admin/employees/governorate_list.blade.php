<label>المحافظة</label>
<select name="governorate_id" id="governorate_id"
    class="form-control select2 {{ $errors->has('governorate_id') ? 'is-invalid' : '' }}">
    <option value="">اختر المحافظة</option>
    @foreach($governorates as $governorate)
    <option value="{{ $governorate->id }}" {{ old('governorate_id', $selected_governorate_id ?? '' )==$governorate->id ?
        'selected' : '' }}>
        {{ $governorate->name }}
    </option>
    @endforeach
</select>
@include('admin.errors.errors', ['value' => 'governorate_id'])