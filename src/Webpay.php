<?php

namespace DanialPanah\WebPay;


use DanialPanah\WebPay\Payment\Gateway;

class Webpay
{
    public function sendPayment(array $options)
    {
        return Gateway::initiatePayment($options)->send();
    }
}