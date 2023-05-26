<?php

namespace App\Http\Controllers;

use App\BLL\NotificationService;
use App\BLL\RateService;
use App\Models\SubscribeEmailModel;

class NotificationController extends Controller
{
    private $notificationService;
    private $rateService;

    public function __construct(NotificationService $notificationService, RateService $rateService)
    {
        $this->notificationService = $notificationService;
        $this->rateService = $rateService;
    }

    public function subscribe(SubscribeEmailModel $email)
    {
        //ToDo
        return 0;
    }

    public function notificateAll()
    {
        //ToDo
        return 0;
    }
}
