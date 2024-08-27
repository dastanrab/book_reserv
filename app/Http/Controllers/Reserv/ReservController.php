<?php

namespace App\Http\Controllers\Reserv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserv\ReservCreateRequest;
use App\Http\Resources\ReservBookResource;
use App\Mail\ReservMail;
use App\Models\Book;
use App\Models\BookReserv;
use App\Notifications\ReservNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReservController extends Controller
{
    public function index()
    {
        try {
            $reserved_book=BookReserv::query()->select(['user_id','book_id','reserv_end_at'])->where('user_id',auth()->id())->where('status',1)->with('book')->get();
            return apiResponseStandard(data: ReservBookResource::collection($reserved_book), message: 'لیست کتاب های رزرو شده');
        }catch (\Exception $exception)
        {
            report($exception);
            return apiResponseStandard(message: 'خطا در لیست کتاب های رزرو شده', statusCode: 500);
        }

    }
    public function reserv(ReservCreateRequest $request)
    {
        $inputs=$request->all();
        $status=200;
        try {
            DB::beginTransaction();
            $book=Book::query()->where('id',$inputs['book_id'])->first();
            if ($book->reserved == 0)
            {
                $user=auth()->user();
                $inputs['user_id']=$user->id;
                $inputs['reserv_end_at']=Carbon::now()->addWeek();
                $book->update(['reserved'=>1]);
                BookReserv::query()->create($inputs);
                $user->notify(new ReservNotification($book));
                $message='رزرو با موفقیت انجام شد';
            }
            else{
                $message='شما قادر به رزرو این کتاب نیستید';
                $status=403;
            }
            DB::commit();
            return apiResponseStandard(message: $message, statusCode: $status);

        }catch (\Exception $exception)
        {
            DB::rollBack();
            report($exception);
            return apiResponseStandard(message: 'خطا در رزرو کتاب', statusCode: 500);
        }
    }
    public function search(Request $request)
    {
        try {
            $fields=$request->all();
            $data=Book::query()->with(['images','writer','categories'])
                ->when(isset($fields['title']) && filled($fields['title']), function ($query) use ($fields) {
                    $query->Where('title','like','%'.$fields['title'].'%');
                })
                ->when(isset($fields['writer']), function ($query) use ($fields) {
                    $query->whereIn('writer_id',$fields['writer']);
                })
                ->when(isset($fields['categories']) && filled($fields['categories'] ) && is_array($fields['categories']), function ($query) use ($fields) {
                    $query->whereHas('categories', function($q) use ($fields) {
                        $q->whereIn('categories.id', $fields['categories']);
                    });
                })->orderBy('id','desc')
                ->get();
            return apiResponseStandard(data: $data);

        }catch (\Exception $exception)
        {
            report($exception);
            return apiResponseStandard(message: 'خطا در جستجو کتاب', statusCode: 500);
        }

    }
}
