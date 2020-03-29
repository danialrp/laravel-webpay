<?php

namespace DanialPanah\WebPay\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    private static $apiUrl = 'https://webpay.bahamta.com/api/create_request/';

    private static $query = [];

    /**
     * @return ResponseInterface
     */
    private static function createHttpRequest(): ResponseInterface
    {
        return (new Client())->request('GET', static::$apiUrl, static::$query);
    }

    /**
     * @param array $query
     * @return mixed
     */
    public static function sendHttpRequest($query = []): array
    {
        static::$query = $query;
        try {
            return json_decode(static::createHttpRequest()->getBody());
        } catch (ClientException $exception) {
            throw $exception;
        }
    }

}