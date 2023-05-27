<?php

namespace App\Http\Controllers;

use App\BLL\NotificationService;
use App\BLL\RateService;
use App\Models\SubscribeEmailModel;

class NotificationController extends Controller
{
    /** @var NotificationService */
    private $notificationService;
    /** @var RateService */
    private $rateService;

    /**
     * @param NotificationService $notificationService
     * @param RateService $rateService
     */
    public function __construct(NotificationService $notificationService, RateService $rateService)
    {
        $this->notificationService = $notificationService;
        $this->rateService = $rateService;
    }

    /**
     * Subscribe API realisation
     *
     * @param SubscribeEmailModel $email HTTP POST Model that contains email
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(SubscribeEmailModel $email)
    {
        request()->validate([
            "email" => "required"
        ]);

        $email = request("email");
        //ToDo Validate email

        return response()->json("", ($this->notificationService->subscribe($email)) ? 200 : 409);
    }

    /**
     * SendEmails API realisation
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function notificateAll()
    {
        $this->notificationService->notificateAll($this->rateService->getBtcUahRate());
        return response()->json("", 200);
    }
}
