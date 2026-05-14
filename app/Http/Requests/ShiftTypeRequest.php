<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ShiftTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "type" => "required|numeric|in:1,2",
            "start_time" => "required|date_format:H:i",
            "end_time" => "required|date_format:H:i",
            "total_hours" => "required|numeric|min:0",
            "status" => "required|numeric|in:0,1",
        ];
    }
    public function messages()
    {
        return [
            "type.required" => "الرجاء اختيار نوع الشفت",
            "type.numeric" => "الرجاء اختيار نوع الشفت نهارى او ليلى",
            "type.in" => "الرجاء اختيار نوع الشفت نهارى او ليلى",
            "start_time.required" => "الرجاء اختيار وقت البدء",
            "start_time.date_format" => "الرجاء اختيار وقت البدء",
            "end_time.required" => "الرجاء اختيار وقت الانتهاء",
            "end_time.date_format" => "الرجاء اختيار وقت الانتهاء",
            "total_hours.required" => "الرجاء إدخال عدد الساعات",
            "total_hours.numeric" => "الرجاء إدخال عدد ساعات صحيح",
            "total_hours.min" => "الرجاء إدخال عدد ساعات موجب",
            "status.required" => "الرجاء اختيار الحالة",
            "status.numeric" => "الرجاء اختيار الحالة مفعل او معطل",
            "status.in" => "الرجاء اختيار الحالة مفعل او معطل",
        ];
    }
}