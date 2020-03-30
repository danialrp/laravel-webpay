<?php

/**
 * Initial Version Created by Danial Panah
 * Web: danialrp.com
 * Email: me@danialrp.com
 * on 3/28/2020 AD - 10:52 PM
 */

namespace DanialPanah\WebPay\Tests;

use DanialPanah\WebPay\WebpayServiceProvider;
use Faker\Factory;
use Faker\Generator;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @var Generator A Faker fake data generator.
     */
    protected $faker;

    public function setUp(): void
    {
        //Create a new faker instance
        $this->faker = Factory::create();

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