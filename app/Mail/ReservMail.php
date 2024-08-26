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

    private string $book_name;

    public function __construct($book_name)
{
    $this->book_name=$book_name;
}

    public function build()
    {
        return $this->subject('reserve success email')
            ->view('emails.reserve_message',['book_name'=>$this->book_name]);
    }

}
