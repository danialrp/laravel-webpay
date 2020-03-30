<?php

namespace DanialPanah\WebPay\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    /**
     * @param $url
     * @param array $query
     * @return ResponseInterface
     */
    private static function createHttpRequest($url, array $query = []): ResponseInterface
    {
        try {
            return (new Client())->request('GET', $url, $query);
        } catch (ClientException $exception) {
            throw $exception;
        }
    }

    /**
     * @param $url
     * @param $query
     * @return mixed
     */
    public static function sendHttpRequest($url, array $query = []): array
    {
        return json_decode(static::createHttpRequest($url, $query)->getBody());
    }

}