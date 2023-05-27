<?php

namespace App\BLL;

use App\Utils\FileDatabase;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /** @var FileDatabase */
    protected $email_db;

    public function __construct()
    {
        $this->email_db = new FileDatabase(env("FILE_DB_FOLDER"), env("FILE_DB_EMAILS_NAME"), env("FILE_DB_EMAILS_DELIMITER"), env("FILE_DB_PART_SIZE"));
    }

    /**
     * Add email to file database
     *
     * @param string $email Email
     * @return bool if this email is already in the database then false is returned else true
     */
    public function subscribe(string $email)
    {
        if ($this->email_db->contains($email))
            return false;

        $this->email_db->add($email);
        return true;
    }

    /**
     * Notificate all users by emails
     *
     * @param float $rate Current bitcoin rate
     * @return void
     */
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
