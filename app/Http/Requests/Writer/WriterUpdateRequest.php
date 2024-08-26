<?php

namespace App\Http\Requests\Writer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class WriterUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function prepareForValidation()
    {
        if (($this->input('first_name') !== null) and ($this->input('last_name') !== null))
        {
            $this->merge([
                'slug' => get_slug_string($this->input('first_name').' '.$this->input('last_name')),
            ]);
        }

    }
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'slug'=>['required',Rule::unique('writers','slug')]

        ];
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(apiResponseStandard(data:[],message:'خطا در داده های ورودی',statusCode: 422,errors: $validator->errors()->getMessages()));
    }
}
