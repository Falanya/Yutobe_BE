<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Bạn chưa nhập tên',
            'name.string' => 'Tên phải chứa cả chữ',
            'email.required' => 'Bạn chưa nhập email',
            'email.email' => 'Sai định dạng email',
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
