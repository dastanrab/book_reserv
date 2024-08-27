<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservMail extends Mailable
{
    use Queueable, SerializesModels;

    private  $book;

    public function __construct($book)
{
    $this->book=$book;
}

    public function build()
    {
        return $this->subject('reserve success email')
            ->view('emails.reserve_message',['book_name'=>$this->book->title]);
    }
    public function view($view, array $data = [])
    {
        return parent::view($view, $data); // TODO: Change the autogenerated stub
    }

}
