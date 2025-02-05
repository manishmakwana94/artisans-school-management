<?php

namespace App\Jobs;

use App\Mail\AnnouncementMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAnnouncementEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $announcement;
    protected $recipients;
    protected $senderName;

    public function __construct($announcement, $recipients, $senderName)
    {
        $this->announcement = $announcement;
        $this->recipients = $recipients;
        $this->senderName = $senderName;
    }

    public function handle()
    {
        foreach ($this->recipients as $recipient) {
            Mail::to($recipient->email)->send(
                new AnnouncementMail(
                    $recipient->name,
                    $recipient->email,
                    $this->senderName,
                    $this->announcement['subject'],
                    $this->announcement['content']
                )
            );
        }
    }
}
