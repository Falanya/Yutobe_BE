<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChannelRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'avatar' => 'required|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|string',
            'slug' => 'required|string|unique:channels,slug',
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => 'Hình ảnh chưa được cập nhật',
            'avatar.mimes' => 'Định dạng file không đúng',
            'avatar.max' => 'Kích thước file quá lớn',
            'name.required' => 'Tên kênh chưa được điền',
            'name.string' => 'Tên kênh phải chứa cả chữ',
            'slug.required' => 'Đường dẫn không được bỏ trống',
            'slug.string' => 'Đường phải chứa cả chữ',
            'slug.unique' => 'Đường dẫn đã tồn tại',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data send',
            'errors' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
