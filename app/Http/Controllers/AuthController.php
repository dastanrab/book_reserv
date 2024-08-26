<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $inputs=$request->all();
            $inputs['password']=Hash::make($inputs['password']);
            $user=User::query()->create($inputs);
            return apiResponseStandard(data:['token'=>$user->createToken('USER_TOKEN')->plainTextToken],message: 'ثبت نام با موفقیت انجام شد');
        }catch (\Exception $exception){
            return apiResponseStandard(data:[],message: 'خطا در ثبت نام کاربر',statusCode: 500);
        }

    }
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return apiResponseStandard(data:[],message: 'اطلاعات ثبت نام نامعتبر است',statusCode: 401);
        }
        return apiResponseStandard(data:['token'=>Auth::user()->createToken('USER_TOKEN')->plainTextToken],message: 'ورود با موفقیت انجام شد');
    }
}
