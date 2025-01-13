<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VideoRequest extends FormRequest
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
            'video' => "required|mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:100040",
            'thumbnail' => "required|mimes:jpeg,png,jpg|max:2048",
            'title' => "required|string",
            'description' => "required|string",
        ];
    }

    public function messages(): array
    {
        return [
            'video.required' => 'File video chưa được tải lên',
            'video.mimes' => 'Định dạng file video không đúng',
            'video.max' => 'Kích thước file quá lớn',
            'thumbnail.required' => "File ảnh chưa được tải lên",
            "thumbnail.mines" => "Định dạng file ảnh không đúng",
            "title.required" => "Vui lòng đặt tiêu đề cho video",
            "title.string" => "Tiêu đề video phải gồm cả chữ",
            "description.required" => "Vui lòng điền mô tả video",
            "description.string" => "Mô tả video phải gồm cả chữ",
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
