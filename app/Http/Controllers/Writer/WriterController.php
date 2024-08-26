<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Writer\WriterCreateRequest;
use App\Http\Requests\Writer\WriterUpdateRequest;
use App\Models\writer;
use Illuminate\Http\Request;

class WriterController extends Controller
{
    public function index()
    {
        return apiResponseStandard(data:Writer::all(),message: 'لیست نویسنده ها');
    }
    public function store(WriterCreateRequest $request){
        try {
            $writer=Writer::query()->create($request->all());
            return apiResponseStandard(data:$writer,message: 'نویسنده با موفقیت ایجاد شد');
        }catch (\Exception $exception){
            report($exception);
            return apiResponseStandard(data:[],message: 'خطا در ثبت نام نویسنده',statusCode: 500);
        }

    }
    public function update(WriterUpdateRequest $request , $writer_id){
        try {
            $writer=Writer::query()->where('id',$writer_id)->first();
            if ($writer)
            {
                $writer->update($request->all());
                return apiResponseStandard(data:$writer->refresh(),message: 'نویسنده با موفقیت بروزرسانی شد');
            }
            return apiResponseStandard(message: 'نویسنده ای پیدا نشد',statusCode: 404);


        }catch (\Exception $exception){
            report($exception);
            return apiResponseStandard(data:[],message: 'خطا در بروزرسانی نویسنده',statusCode: 500);
        }

    }
    public function destroy($writer_id)
    {
        try {
            $writer=Writer::query()->where('id',$writer_id)->first();
            if ($writer)
            {
                $writer->delete();
                return apiResponseStandard(data:$writer->refresh(),message: 'نویسنده با موفقیت حذف شد');
            }
            return apiResponseStandard(message: 'نویسنده ای پیدا نشد',statusCode: 404);


        }catch (\Exception $exception){
            report($exception);
            return apiResponseStandard(data:[],message: 'خطا در حذف نویسنده',statusCode: 500);
        }
    }
}
