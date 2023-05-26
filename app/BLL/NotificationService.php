<?php

namespace App\BLL;

use App\Utils\FileDatabase;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    protected $email_db;

    public function __construct()
    {
        $this->email_db = new FileDatabase(env("FILE_DB_FOLDER"), env("FILE_DB_EMAILS_NAME"), env("FILE_DB_EMAILS_DELIMITER"), env("FILE_DB_PART_SIZE"));
    }

    public function subscribe(string $email)
    {
        if ($this->email_db->contains($email))
            return false;

        $this->email_db->add($email);
        return true;
    }

    public function notificateAll(float $rate)
    {
        $emails = $this->email_db->getAll();
        $offset = 0;
        while ($offset < count($emails)) {
            $emails_part = array_slice($emails, $offset, env("MAIL_OFFSET"));

            dispatch(function () use ($rate, $emails_part) {
                Mail::raw(sprintf(env("MAIL_TEMPLATE"), $rate), function ($message) use ($emails_part) {
                    $message->subject(env("MAIL_TITLE"))
                        ->from(env("MAIL_FROM"))
                        ->to($emails_part);
                });
            });

            $offset += env("MAIL_OFFSET");
        }
    }
}
