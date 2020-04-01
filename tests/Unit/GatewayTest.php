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
use DanialPanah\WebPay\Payment\Gateway;
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
                'payer_mobile' => '',
                'cards' => ''
        ];
    }

    public function proper_config_api_key()
    {
        Config::set('webpay.api_key', $this->faker->text(30));
    }

    public function proper_config_callback_url()
    {
        Config::set('webpay.callback_url', '/payment/test');
    }

    public function test_api_key()
    {
        Config::set('webpay.api_key', null);

        $this->proper_config_callback_url();

        $this->expectException(WebpayException::class);
        $this->expectExceptionMessage('api_key is not set.');

        Webpay::sendPayment($this->samplePayment);
    }

    public function test_callback_url()
    {
        $this->proper_config_api_key();

        Config::set('webpay.callback_url', null);

        $this->expectException(WebpayException::class);
        $this->expectExceptionMessage('callback_url is not set.');

        Webpay::sendPayment($this->samplePayment);
    }

    public function test_amount_irr()
    {
        $this->proper_config_api_key();
        $this->proper_config_callback_url();

        $this->samplePayment['amount'] = null;

        $this->expectException(WebpayException::class);
        $this->expectExceptionMessage('amount is not set.');

        Webpay::sendPayment($this->samplePayment);
    }

    public function test_reference_number()
    {
        $this->proper_config_api_key();
        $this->proper_config_callback_url();

        $this->samplePayment['reference_number'] = null;

        $this->expectException(WebpayException::class);
        $this->expectExceptionMessage('reference is not set.');

        Webpay::sendPayment($this->samplePayment);
    }
}