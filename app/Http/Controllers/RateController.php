<?php

namespace App\Http\Controllers;

use App\BLL\RateService;
use Carbon\Exceptions\InvalidTypeException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class RateController extends Controller
{
    private $rateService;

    public function __construct(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

    public function index()
    {
        try {
            $rate = $this->rateService->getBtcUahRate();
        } catch (GuzzleException | AccessDeniedException | InvalidTypeException $e) {
            return response("", 400);
        }

        return response()->json($rate);
    }
}
