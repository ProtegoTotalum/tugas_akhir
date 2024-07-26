<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class DiagnosisNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $diagnosisId;

    public function __construct($diagnosisId)
    {
        $this->diagnosisId = $diagnosisId;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setContent([
                'title' => 'New Diagnosis',
                'body' => 'A new diagnosis needs your review',
            ])
            ->setData([
                'diagnosis_id' => $this->diagnosisId,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'diagnosis_id' => $this->diagnosisId,
        ];
    }
}