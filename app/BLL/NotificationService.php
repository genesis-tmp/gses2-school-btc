<?php

namespace App\BLL;

use App\Utils\FileDatabase;

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

}
