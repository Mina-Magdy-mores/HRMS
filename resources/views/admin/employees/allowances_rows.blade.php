@forelse ($fixedAllowances as $allowance)
    <tr>
        <td>{{ $allowance->id }}</td>
        <td>{{ $allowance->allowanceType->name ?? '---' }}</td>
        <td>{{ $allowance->amount }}</td>
        <td>{{ $allowance->created_at }}</td>
        <td>{{ $allowance->updated_at }}</td>
        <td>
            <button type="button" class="btn btn-success btn-sm edit_allowance_btn" 
                data-id="{{ $allowance->id }}" 
                data-type-id="{{ $allowance->allowance_type_id }}" 
                data-amount="{{ $allowance->amount }}">
                <i class="fas fa-edit"></i>
                تعديل
            </button>
            <button type="button" class="btn btn-danger btn-sm delete_allowance_btn" data-id="{{ $allowance->id }}">
                <i class="fas fa-trash"></i>
                حذف
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6">
            <div class="alert alert-warning mb-0">
                <i class="fas fa-exclamation-circle"></i>
                لا توجد بدلات ثابتة مضافة حالياً
            </div>
        </td>
    </tr>
@endforelse
