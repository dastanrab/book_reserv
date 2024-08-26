<?php

namespace App\Http\Controllers\Reserv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserv\ReservCreateRequest;
use App\Mail\ReservMail;
use App\Models\Book;
use App\Models\BookReserv;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReservController extends Controller
{
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
                $book->update(['reserved'=>2]);
                BookReserv::query()->create($inputs);
                Mail::to($user->email)->send(new ReservMail($book->title));
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
}
