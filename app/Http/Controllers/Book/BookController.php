<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookCreateRequest;
use App\Http\Requests\Book\BookUpdateRequest;
use App\Models\Book;
use App\Models\BookImage;
use App\Models\Category;
use Illuminate\Http\Request;
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
       $book=Book::query()->create($request->only(['title','writer_id']));
        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $image->store('blogs', 'public');
                $bookImage = new BookImage();
                $bookImage->url = $filename;
                $bookImage->book_id = $book->id;
                $bookImage->save();

            }
        }
        $categories=Category::query()->whereIn('id',$request->input('categories'))->get()->pluck('id')->toArray();
        if (count($categories)>0)
        {
            $book->categories()->syncWithoutDetaching($categories);
        }
        return apiResponseStandard(data: $book->refresh(), message: 'کتاب با موفقیت ایجاد شد', statusCode: 200);
    }catch (\Exception $exception){
     report($exception);
        return apiResponseStandard(data:[],message: 'خطا در ایجاد کتاب',statusCode: 500);
    }

}
    public function update(BookUpdateRequest $request,$book_id  )
    {
        try {
            $book=Book::query()->where('id',$book_id)->first();
            if ($book)
            {
                $book->update($request->only(['title','writer_id']));
                if ($request->has('images')) {
                    foreach ($request->file('images') as $image) {
                        $filename = $image->store('blogs', 'public');
                        $bookImage = new BookImage();
                        $bookImage->url = $filename;
                        $bookImage->book_id = $book->id;
                        $bookImage->save();
                    }
                }
                $categories=Category::query()->whereIn('id',$request->input('categories'))->get()->pluck('id')->toArray();
                if (count($categories)>0)
                {
                    $book->categories()->syncWithoutDetaching($categories);
                }
                return apiResponseStandard(data: $book->refresh(), message: 'کتاب با موفقیت ایجاد شد', statusCode: 200);
            }
            return apiResponseStandard( message: 'کتابی یافت نشد', statusCode: 404);
        }catch (\Exception $exception){
            report($exception);
            return apiResponseStandard(data:[],message: 'خطا در ایجاد کتاب',statusCode: 500);
        }

    }
    public function destroy($book_id)
    {
        try {
            $book=Book::query()->where('id',$book_id)->first();
            if ($book)
            {
                    $images=$book->images();
                    foreach ($images->get() as $image)
                    {
                        if (Storage::disk('public')->exists($image->url))
                        {
                            Storage::disk('public')->delete($image->url);
                        }
                    }
                    $images->delete();
                    $book->categories()->delete();
                    $book->delete();
                    return apiResponseStandard( message: 'کتاب با موفقیت حذف شد');
                }

            return apiResponseStandard( message: 'کتابی یافت نشد', statusCode: 404);
        }catch (\Exception $exception){
            report($exception);
            return apiResponseStandard(data:[],message: 'خطا در حذف کتاب',statusCode: 500);
        }

    }
}
