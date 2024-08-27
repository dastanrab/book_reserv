<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Writer extends Model
{
    use HasFactory;
    protected $fillable = ['id','first_name','last_name','slug'];
    protected $table = 'writers';
    public function book()
    {
        return $this->hasMany(Book::class,'writer_id','id');
    }
}
