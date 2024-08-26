<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'role' => ['required', 'in:admin,role'],
            'password'=>['required','string'],
            'email'=>['required','email','unique:users,email']
        ];
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(apiResponseStandard(data:[],message:'خطا در داده های ورودی',statusCode: 422,errors: $validator->errors()->getMessages()));
    }
}
