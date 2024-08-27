<?php

namespace App\Http\Resources;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'book_id'=>$this->book_id,
            'book_title'=>$this->book->title,
            'reserv_end_at'=>$this->reserv_end_at,
            'reserver fullname'=>@Auth()->user()->first_name.' '.@Auth()->user()->last_name,
            'reserv_status'=>$this->status
        ];
    }
}
