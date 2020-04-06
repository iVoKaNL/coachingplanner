<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyStudents extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $student;
    private $guid;
    private $fullname;
    public function __construct($student, $guid, $fullname)
    {
        $this->student = $student;
        $this->guid = $guid;
        $this->fullname = $fullname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mail@example.com', 'Mailtrap')
            ->subject('Mailtrap Confirmation')
            ->view('emails.NotifyStudents', [
                'student' => $this->student,
                'guid' => $this->guid,
                'fullname' => $this->fullname
            ]);
    }
}
