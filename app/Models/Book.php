<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['title','writer_id','reserved'];
    protected $table = 'books';
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class,'book_category','book_id', 'category_id');
    }
    public function images()
    {
        return $this->hasMany(BookImage::class,'book_id','id');
    }
    public function writer()
    {
        return $this->belongsTo(Writer::class,'writer_id');
    }

}
