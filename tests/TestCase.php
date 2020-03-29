<?php

namespace DanialPanah\WebPay\Tests;


use DanialPanah\WebPay\WebpayServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            WebpayServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Environment Setup
    }
}