<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReserv extends Model
{
    use HasFactory;
    protected $fillable=['id','book_id','user_id','reserv_end_at'];

    public function book()
    {
        return $this->belongsTo(Book::class,'book_id','id');
    }
}
