<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookCreateRequest;
use App\Http\Requests\Book\BookUpdateRequest;
use App\Models\Book;
use App\Models\BookImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        return apiResponseStandard(data:Book::query()->with(['writer','images','categories'])->get(),message: 'لیست نویسنده ها');

    }
    public function store(BookCreateRequest $request)
{
    try {
        DB::beginTransaction();
       $book=Book::query()->create($request->only(['title','writer_id']));
       foreach ($request->file('images') as $image)
       {
                $filename = $image->store('blogs', 'public');
                $book->images()->create(['url'=>$filename]);
       }
        $categories=Category::query()->whereIn('id',$request->input('categories'))->get()->pluck('id')->toArray();
        if (count($categories)>0)
        {
            $book->categories()->attach($categories);
        }
        DB::commit();
        return apiResponseStandard(data: $book->refresh(), message: 'کتاب با موفقیت ایجاد شد', statusCode: 200);
    }catch (\Exception $exception){
        DB::rollBack();
        report($exception);
        return apiResponseStandard(data:[],message: 'خطا در ایجاد کتاب',statusCode: 500);
    }
}
    public function update(BookUpdateRequest $request,$book_id  )
    {
        $book=Book::query()->where('id',$book_id)->first();
        if (!$book){
            return apiResponseStandard( message: 'کتابی یافت نشد', statusCode: 404);
        }
        try {
            DB::beginTransaction();
            $book->update($request->only(['title','writer_id']));
            $book->images()->delete();
            foreach ($request->file('images') as $image)
            {
                $filename = $image->store('blogs', 'public');
                $bookImage = new BookImage();
                $bookImage->url = $filename;
                $bookImage->book_id = $book->id;
                $bookImage->save();
            }
            $categories=Category::query()->whereIn('id',$request->input('categories'))->get()->pluck('id')->toArray();
            if (count($categories)>0)
            {
                $book->categories()->sync($categories);
            }
            DB::commit();
            return apiResponseStandard(data: $book->refresh(), message: 'کتاب با موفقیت ایجاد شد', statusCode: 200);
        }catch (\Exception $exception){
            DB::rollBack();
            report($exception);
            return apiResponseStandard(data:[],message: 'خطا در ایجاد کتاب',statusCode: 500);
        }
    }
    public function destroy($book_id)
    {
        $book=Book::query()->where('id',$book_id)->first();
        if (!$book)
        {
            return apiResponseStandard( message: 'کتابی یافت نشد', statusCode: 404);
        }
        if ($book->reserve_books()->where('status',1)->count()>0)
        {
            return apiResponseStandard( message: 'این کتاب توسط کاربری رزرو شده و قادر به حذف نیستید', statusCode: 403);
        }
            try {
                $images=$book->images();
                foreach ($images->get() as $image)
                {
                    if (Storage::disk('public')->exists($image->url))
                    {
                        Storage::disk('public')->delete($image->url);
                    }
                }
                $images->delete();
                $book->categories()->detach();
                $book->delete();
                return apiResponseStandard( message: 'کتاب با موفقیت حذف شد');
            }catch (\Exception $exception){
                report($exception);
                return apiResponseStandard(data:[],message: 'خطا در حذف کتاب',statusCode: 500);
            }
    }
}
