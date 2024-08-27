<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return apiResponseStandard(data:Category::all(),message: 'لیست نویسنده ها');
    }
    public function store(CategoryCreateRequest $request)
    {
        try {
            $writer=Category::query()->create($request->all());
            return apiResponseStandard(data:$writer,message: 'دسته بندی با موفقیت ایجاد شد');
        }catch (\Exception $exception){
            report($exception);
            return apiResponseStandard(data:[],message: 'خطا در ثبت نام دسته بندی',statusCode: 500);
        }
    }
    public function update(CategoryUpdateRequest $request,$category_id){
        try {
            $category=Category::query()->where('id',$category_id)->first();
            if ($category)
            {
                $category->update($request->all());
                return apiResponseStandard(data:$category->refresh(),message: 'دسته بندی با موفقیت بروزرسانی شد');
            }
            return apiResponseStandard(message: 'دسته بندی ای پیدا نشد',statusCode: 404);


        }catch (\Exception $exception){
            report($exception);
            return apiResponseStandard(data:[],message: 'خطا در بروزرسانی دسته بندی',statusCode: 500);
        }
    }
    public function destroy($category_id)
    {
        try {
            $category=Category::query()->where('id',$category_id)->first();
            if (!$category)
            {
                return apiResponseStandard(message: 'دسته بندی ای پیدا نشد',statusCode: 404);
            }
            if ($category->books()->count()>0)
            {
                return apiResponseStandard( message: 'این دسته بندی توسط کتاب استفاده شده و قادر به حذف نیستید', statusCode: 403);
            }
            $category->delete();
            return apiResponseStandard(data:$category->refresh(),message: 'دسته بندی با موفقیت حذف شد');
        }catch (\Exception $exception){
            report($exception);
            return apiResponseStandard(data:[],message: 'خطا در حذف دسته بندی',statusCode: 500);
        }
    }
}
