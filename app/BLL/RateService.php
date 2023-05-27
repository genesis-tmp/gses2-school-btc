<?php

namespace App\BLL;

use Carbon\Exceptions\InvalidTypeException;
use GuzzleHttp\Client;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class RateService
{
    /**
     * Get bitcoin rate via bitpay api
     *
     * @return float Bitcoin rate
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws AccessDeniedException
     * @throws InvalidTypeException
     */
    public function getBtcUahRate()
    {
        $client = new Client();
        $res = $client->request("GET", "https://bitpay.com/api/rates/uah");

        if ($res->getStatusCode() != 200)
            throw new AccessDeniedException("Can't use BitPay api.");

        $rate = json_decode($res->getBody(), true)["rate"];

        if (((gettype($rate) != "double")) && ((gettype($rate) != "integer")))
            throw new InvalidTypeException("Rate value must be double or integer.");

        return $rate;
    }
}
