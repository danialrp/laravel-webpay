<?php
/**
 * Initial Version Created by Danial Panah
 * Web: danialrp.com
 * Email: me@danialrp.com
 * on 3/30/2020 AD - 10:23 PM
 */

namespace DanialPanah\WebPay\Tests\Units;

use DanialPanah\WebPay\Http\HttpClient;
use DanialPanah\WebPay\Tests\TestCase;
use GuzzleHttp\Exception\RequestException;

class HttpClientTest extends TestCase
{
    public function test_client_api_url()
    {
        $this->expectException(RequestException::class);

        $apiUrl = '';
        HttpClient::sendHttpRequest($apiUrl);
    }
}