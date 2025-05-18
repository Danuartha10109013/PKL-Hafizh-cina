<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class AttendanceReminder extends Mailable
{
    public $user;
    public $pdfContent;
    public $filename;
    public $data;

    // Constructor untuk terima user dan PDF content
    public function __construct(User $user, $pdfContent, $filename,$data)
    {
        $this->user = $user;
        $this->pdfContent = $pdfContent;
        $this->filename = $filename;
        $this->data = $data;
    }

    // Build the email with attachment
    public function build()
    {
        return $this->subject('Peringatan Kehadiran')
                    ->view('emails.attendance_reminder')
                    ->with([
                    'data' => $this->data, // data untuk Blade view
                    ])
                    ->attachData($this->pdfContent, $this->filename, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
