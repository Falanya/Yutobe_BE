<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            'email' => 'required|email',
            'name' => 'required|string',
            'avatar' => 'required|string',
            'social_id' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Sai định dạng email, ví dụ: Abc@gmail.com',
            'name.required' => 'Tên không được để trống',
            'name.string' => 'Tên không được chứa số',
            'avatar.required' => 'Ảnh không được để trống',
            'avatar.string' => 'Ảnh sai định dạng',
            'social_id.required' => 'Id không được để trống',
            'social_id.string' => 'Id sai định dạng',
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
