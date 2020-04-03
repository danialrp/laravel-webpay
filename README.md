# Laravel Webpay Payment Gateway (Bahamta)

[![Build Status](https://travis-ci.org/danialrp/laravel-webpay.svg?branch=master)](https://travis-ci.org/github/danialrp/laravel-webpay)
[![Latest Release on Packagist](https://img.shields.io/packagist/v/danialpanah/webpay.svg?style=flat-square)](https://packagist.org/packages/danialpanah/webpay)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

- [Introduction](#introduction)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Support & Security](#support-security)
- [Webpay Official Documentation](https://webpay.bahamta.com/doc/api)
- [License](#license)

<a name="introduction"></a>
## Introduction

Laravel package for connecting and accept payments via Webpay payment gateway in your Laravel application.

<a name="installation"></a>
## Installation

* Use following command to install:
```bash
composer require danialpanah/webpay
```
**This package supports Laravel auto-discovery feature. If you are using Laravel 5.5 or greater no need to do any further actions, otherwise follow below steps.**

* Add the service provider to your `providers[]` array in `config/app.php` in your laravel application: 
```php
DanialPanah\Webpay\WebpayServiceProvider::class
```

* For using Laravel Facade add the alias to your `aliases[]` array in `config/app.php` in Laravel: 
```php
'Webpay': DanialPanah\Webpay\Facades\Webpay::class
```

<a name="configuration"></a>
## Configuration

* After installation, you need to add your Webpay api settings. You can update **config/webpay.php** published file or in you Laravel **.env** file.

* Run the following command to publish the configuration file:
```bash
php artisan vendor:publish --provider "DanialPanah\Webpay\WebpayServiceProvider"
```

* **config/webpay.php**
```bash
return [
    'api_key' => env('WEBPAY_API_KEY', ''),
    'callback_url' => env('WEBPAY_CALLBACK_URL', '')
];
```

* Add this to `.env.example` and `.env` files:
```
#Webpay API key and Settings

#your webpay api key e.g "webpay:2bbc6c62-4fe..."
WEBPAY_API_KEY=

#address of rediricting after payment e.g "/payment/test"
WEBPAY_CALLBACK_URL=
```

<a name="usage"></a>
## Usage

Following are some approaches which you can have for initiate a payment through Webpay package:

* Initiate Payment and Receive the Payment URL:
```
// Importing the class namespaces before using it
use DanialPanah\WebPay\Webpay;

$samplePayment = [
   'amount' => 10000,  //required
   'reference_number' => '#####',  //required - invoice number(unique)
   'payer_mobile' => '',  //optional - payer mobile number for save/load cards in gateway
   'cards' => '',  //optional - allowed cards for perform payment
];

$webPay = new Webpay();
$paymentUrl = $webPay->sendPayment($samplePayment);
```

* Using Facades (Initiate Payment):
```
use DanialPanah\WebPay\Facades\Webpay;

$paymentUrl = Webpay::sendPayment($samplePayment);
```

* Verify Payment:
```
$paymentDetails = [
   'amount' => 10000,  //required
   'reference_number' => '#####'  //required - invoice number
];

$webPay = new Webpay();
$response = $webPay->verifyPayment($paymentDetails);
```

* Using Facades (Verify Payment):
```
use DanialPanah\WebPay\Facades\Webpay;

$response = Webpay::verifyPayment($paymentDetails);
```

* Sample Response of Verify Successful Payment:
```
array: [
  "ok" => true
  "result" => {
      "state": "paid"
      "total": 10000
      "wage": 1500
      "gateway": "sep"
      "terminal": "11423087"
      "pay_ref": "KmsctypmKSs0WfEB01H3ROJPLN2ZQrauExLR90nnXk"
      "pay_trace": "228945"
      "pay_pan": "610422******8585"
      "pay_cid": "4470D3E90AA5Z3FB7B21B3348D34EE25EC331915BCQP68BC4D0D1C0678548B8D"
      "pay_time": "2020-04-02T17:47:14.391164Z"
  }
]
``` 

* Laravel Sample Controller:
```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DanialPanah\WebPay\Facades\Webpay;

class PaymentController extends Controller
{
    /**
     * @return RedirectResponse|Redirector
     * @throws WebpayException
     */    
     public function pay()
    {
        $userTrustedCards = ['6219196262191962', '6104046161040461'];

        $samplePayment = [
           'amount' => 10000,
           'reference_number' => '999999',
           'payer_mobile' => '09121111111',
           'cards' => $userTrustedCards,
        ];

        $paymentUrl = Webpay::sendPayment($samplePayment);

        return redirect($paymentUrl);
    }


    /**
     * @param Request $request
     * @throws VerifyException
     */
    public function verify(Request $request)
    {
        $paymentDetails = [
            'amount' => 10000,
            'reference_number' => '999999',
        ];

        $response = Webpay::verifyPayment($paymentDetails);

        if(!$reponse['ok'] === true) {
            //Transactions was not successful
        }

        //Do something for successful transactoin

    }
}

```

<a name="support-security"></a>
## Support & Security

This package supports Laravel 5.1 or greater, 6.x and 7.x with PHP 7.2 and above.
* In case of discovering any issues, please create one on the [Issues](https://github.com/danialrp/laravel-webpay/issues) section.
* For contribution, fork this repo and implements your code then create a PR.

<a name="license"></a>
## License

This repository is an open-source software under the [MIT](https://choosealicense.com/licenses/mit/) license.


