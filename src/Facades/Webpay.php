<?php

namespace DanialPanah\WebPay\Facades;

use Illuminate\Support\Facades\Facade;

class Webpay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'webpay';
    }
}