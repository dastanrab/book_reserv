<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class BookCreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            'writer_id' => 'required|exists:writers,id',
            'categories' => 'required|array',
            'categories.*' => 'numeric'
        ];
    }
    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(apiResponseStandard(data: [], message: 'خطا در داده های ورودی', statusCode: 422, errors: $validator->errors()->getMessages()));
    }
}
