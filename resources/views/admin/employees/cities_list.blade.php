<label>المدينة</label>
<select name="city_id" id="city_id"
 class="form-control select2 {{ $errors->has('city_id') ? 'is-invalid' : '' }}">
    <option value="">اختر المدينة</option>
    @foreach($cities as $city)
    <option value="{{ $city->id }}" {{ old('city_id')==$city->id ?
        'selected' : '' }}>
        {{ $city->name }}
    </option>
    @endforeach
</select>
@include('admin.errors.errors', ['value' => 'city_id'])
