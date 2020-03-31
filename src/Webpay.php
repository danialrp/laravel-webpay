<?php

namespace DanialPanah\WebPay;


use DanialPanah\WebPay\Payment\Gateway;

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
}