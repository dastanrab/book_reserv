<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;


class CategoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        if ($this->input('title') !== null) {
            $this->merge([
                'slug' => get_slug_string($this->input('title')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required','string'],
            'slug' => ['required',Rule::unique('categories', 'slug')]
        ];
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(apiResponseStandard(data: [], message: 'خطا در داده های ورودی', statusCode: 422, errors: $validator->errors()->getMessages()));
    }
}
