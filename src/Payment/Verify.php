<?php

namespace DanialPanah\WebPay\Payment;

use DanialPanah\WebPay\Exceptions\VerifyException;
use DanialPanah\WebPay\Http\HttpClient;

class Verify
{
    /**
     * Amount to pay
     *
     * @var int
     */
    private $amount;

    /**
     * Unique transaction number
     *
     * @var string
     */
    private $referenceNumber;

    /**
     * Webpay api key
     *
     * @var string
     */
    private static $api_key;

    /**
     * Webpay verification url
     *
     * @var string
     */
    private static $verifyUrl = 'https://webpay.bahamta.com/api/confirm_payment';

    /**
     * Verify constructor.
     * @param array $details
     * @throws VerifyException
     */
    public function __construct(array $details)
    {
        static::$api_key = config('webpay.api_key') ?? null;

        $this->validConfig($details);
    }

    /**
     * @param array $options
     * @throws VerifyException
     */
    private function validConfig(array $options)
    {
        $reqKeys = [
            'api_key' => static::$api_key,
            'amount' => array_key_exists('amount', $options) ? $options['amount'] : null,
            'reference_number' => array_key_exists('reference_number', $options) ? $options['reference_number'] : null
        ];

        foreach ($reqKeys as $key => $value) {
            if(!$value) {
                throw new VerifyException($key . ' is not set.');
            }
        }

        $this->setProperValues($options);
    }

    /**
     * @param array $values
     */
    private function setProperValues(array $values): void
    {
        [
            'amount' => $this->amount,
            'reference_number' => $this->referenceNumber
        ] = $values;
    }

    /**
     * @return array
     */
    private function makeQueryArray(): array
    {
        return [
            'query' => [
                'api_key' => static::$api_key,
                'reference' => $this->referenceNumber,
                'amount_irr' => $this->amount
            ]
        ];
    }

    /**
     * @param array $details
     * @return Verify
     * @throws VerifyException
     */
    public static function initiateVerify(array $details): Verify
    {
        return new static($details);
    }

    /**
     * @return array
     */
    public function send()
    {
        return $this->getVerifyResult(HttpClient::sendHttpRequest(static::$verifyUrl, $this->makeQueryArray()));
    }

    /**
     * @param array $response
     * @return array
     */
    private function getVerifyResult(array $response): array
    {
        return $response;
    }
}