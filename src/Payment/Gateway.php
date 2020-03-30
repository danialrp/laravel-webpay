<?php

namespace DanialPanah\WebPay\Payment;

use DanialPanah\WebPay\Http\HttpClient;
use http\Exception\InvalidArgumentException;
use Illuminate\Config\Repository;

class Gateway
{
    /**
     * payment api url
     *
     * @var string
     */
    private static $apiUrl= 'https://webpay.bahamta.com/api/create_request/';


    /**
     * Query parameter
     *
     * @param array $options
     * @return array
     */
    private function queryPayload(array $options = [])
    {
        return [
            'query' => [
                'api_key' => self::apiKey(),
                'callback_url' => self::callbackUrl(),
                'reference' => self::reference($options['reference'] ?? ''),
                'amount_irr' => self::amount($options['amount'] ?? ''),
                'payer_mobile' => (string)$options['payer_mobile'] ?? '',
                'trusted_pan' => self::trustedCards($options['trusted_cards'] ?? '')
            ]
        ];
    }

    /**
     * Set api key from config key
     *
     * @return Repository|mixed
     */
    private function apiKey(): string
    {
        $apiKey = config('webpay.api_key');
        if(isset($apiKey))
            return $apiKey;

        throw new InvalidArgumentException('api key is not set.');
    }

    /**
     * Set callback url from config key
     *
     * @return Repository|mixed
     */
    private function callbackUrl(): string
    {
        $callbackUrl = config('webpay.callback_url');
        if(isset($callbackUrl))
            return $callbackUrl;

        throw new InvalidArgumentException('callback url is not set.');
    }

    /**
     * @param string $reference
     * @return string
     */
    protected static function reference($reference): string
    {
        if(empty($reference)) {
            throw new InvalidArgumentException('invalid reference value provided');
        }

        return (string)$reference;
    }

    /**
     * @param $amount
     * @return int
     */
    protected static function amount($amount): int
    {
        if(empty($amount)) {
            throw new InvalidArgumentException('invalid amount value provided');
        }

        return (int)$amount;
    }

    /**
     * Receive cards in string or array
     *
     * @param $trustedCards
     * @return string
     */
    protected static function trustedCards($trustedCards): string
    {
        $trustedCards = (array)$trustedCards;
        return implode(',', $trustedCards);
    }

    /**
     * @param array $params
     * @return array|mixed
     */
    public function initiatePayment(array $params = [])
    {
        return HttpClient::sendHttpRequest(static::$apiUrl, $this->queryPayload($params));
    }
}