<?php
/**
 * Initial Version Created by Danial Panah
 * Web: danialrp.com
 * Email: me@danialrp.com
 * on 4/1/2020 AD - 7:12 PM
 */

namespace DanialPanah\WebPay\Tests\Units;


use DanialPanah\WebPay\Exceptions\WebpayException;
use DanialPanah\WebPay\Facades\Webpay;
use DanialPanah\WebPay\Tests\TestCase;
use Illuminate\Support\Facades\Config;

class GatewayTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->samplePayment = [
                'amount' => $this->faker->randomNumber(4),
                'reference_number' => $this->faker->text(10),
                'payer_mobile' => $this->faker->phoneNumber,
                'cards' => $this->faker->creditCardNumber()
        ];
    }

    public function test_api_key()
    {
        Config::set('webpay.api_key', null);
        Config::set('webpay.callback_url', '/payment/test');

        $this->expectException(WebpayException::class);
        $this->expectExceptionMessage('api_key is not set.');

        Webpay::sendPayment($this->samplePayment);
    }

    public function test_callback_url()
    {
        Config::set('webpay.api_key', $this->faker->text(30));
        Config::set('webpay.callback_url', null);

        $this->expectException(WebpayException::class);
        $this->expectExceptionMessage('callback_url is not set.');

        Webpay::sendPayment($this->samplePayment);
    }
}