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
        request()->validate([
            "email" => "required"
        ]);

        $email = request("email");
        //ToDo Validate email

        return response()->json("", ($this->notificationService->subscribe($email)) ? 200 : 409);
    }

    public function notificateAll()
    {
        $this->notificationService->notificateAll($this->rateService->getBtcUahRate());
        return response()->json("", 200);
    }
}
