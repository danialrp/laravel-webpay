<?php
/**
 * Initial Version Created by Danial Panah
 * Web: danialrp.com
 * Email: me@danialrp.com
 * on 4/2/2020 AD - 10:32 PM
 */

namespace DanialPanah\WebPay\Tests\Units;

use DanialPanah\WebPay\Exceptions\VerifyException;
use DanialPanah\WebPay\Facades\Webpay;
use DanialPanah\WebPay\Tests\TestCase;
use Illuminate\Support\Facades\Config;

class VerifyTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->proper_config();

        $this->sampleVerify = [
            'amount' => $this->faker->randomNumber(4),
            'reference_number' => $this->faker->text(10)
        ];
    }

    public function proper_config()
    {
        Config::set('webpay.api_key', $this->faker->text(30));
    }

    public function test_api_key()
    {
        Config::set('webpay.api_key', null);

        $this->expectException(VerifyException::class);
        $this->expectExceptionMessage('api_key is not set.');

        Webpay::verifyPayment($this->sampleVerify);
    }

    public function test_amount_irr()
    {
        $this->sampleVerify['amount'] = null;

        $this->expectException(VerifyException::class);
        $this->expectExceptionMessage('amount is not set.');

        Webpay::verifyPayment($this->sampleVerify);
    }

    public function test_reference_number()
    {
        $this->sampleVerify['reference_number'] = null;

        $this->expectException(VerifyException::class);
        $this->expectExceptionMessage('reference_number is not set.');

        Webpay::verifyPayment($this->sampleVerify);
    }
}