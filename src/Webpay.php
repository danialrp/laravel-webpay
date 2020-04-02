<?php

namespace DanialPanah\WebPay;


use DanialPanah\WebPay\Payment\Gateway;
use DanialPanah\WebPay\Payment\Verify;

class Webpay
{
    /**
     * @param array $options
     * @return array|mixed
     * @throws Exceptions\WebpayException
     */
    public function sendPayment(array $options)
    {
        return Gateway::initiatePayment($options)->send();
    }

    /**
     * @param array $options
     * @return string
     * @throws Exceptions\VerifyException
     */
    public function verifyPayment(array $options)
    {
        return Verify::initiateVerify($options)->send();
    }
}