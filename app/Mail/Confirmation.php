<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\CalendarLinks\Link;

class Confirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $student;
    private $agenda;
    public function __construct($student, $agenda)
    {
        $this->student = $student;
        $this->agenda = $agenda;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mail@example.com', 'Mailtrap')
            ->subject('Moment Confirmation')
            ->view('emails.Confirmation', [
                'student' => $this->student,
                'agenda' => $this->agenda,
            ]);
    }
}
