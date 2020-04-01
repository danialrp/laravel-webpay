<?php

namespace DanialPanah\WebPay\Payment;

use DanialPanah\WebPay\Http\HttpClient;
use DanialPanah\WebPay\Exceptions\WebpayException;

class Gateway
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
     * Payer mobile number
     *
     * @var string
     */
    private $payerMobile;

    /**
     * Trusted payer card number(s)
     *
     * @var array|string
     */
    private $trustedCards;

    /**
     * Webpay api key
     *
     * @var string
     */
    private static $apiKey;

    /**
     * Redirection url
     *
     * @var string
     */
    private static $callbackUrl;

    /**
     * payment api url
     *
     * @var string
     */
    private static $apiUrl = 'https://webpay.bahamta.com/api/create_request';

    /**
     * Gateway constructor.
     * @param array $options
     * @throws WebpayException
     */
    public function __construct(array $options)
    {
        static::$callbackUrl = config('webpay.callback_url') ?? null;
        static::$apiKey = config('webpay.api_key') ?? null;

        $this->setupDefaultValues($options);
    }

    /**
     * @param array $options
     * @throws WebpayException
     */
    private function setupDefaultValues(array $options): void
    {
        $this->validateDefaultValues($options);

        [
            'amount' => $this->amount,
            'reference_number' => $this->referenceNumber
        ] = $options;

        if (array_key_exists('payer_mobile', $options)) {
            $this->setPayerMobile($options['payer_mobile']);
        }

        if (array_key_exists('cards', $options)) {
            $this->setTrustedCards($options['cards']);
        }
    }


    /**
     * @param array $options
     * @throws WebpayException
     */
    private function validateDefaultValues(array $options): void
    {
        $values = [
            'amount' => array_key_exists('amount', $options) ? $options['amount'] : null,
            'reference' => array_key_exists('reference_number', $options) ? $options['reference_number'] : null,
            'callback_url' => static::$callbackUrl,
            'api_key' => static::$apiKey
        ];

        foreach ($values as $key => $value) {
            if (!$value)
                throw new WebpayException($key . ' is not set.');
        }
    }

    /**
     * Make payment details array
     *
     * @return array
     */
    private function makeQueryArray(): array
    {
        return [
            'query' => [
                'api_key' => static::$apiKey,
                'callback_url' => url('/') . static::$callbackUrl,
                'amount_irr' => $this->amount,
                'reference' => $this->referenceNumber,
                'payer_mobile' => $this->payerMobile,
                'trusted_pan' => $this->trustedCards
            ]
        ];
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @param string $referenceNumber
     */
    public function setReferenceNumber(string $referenceNumber): void
    {
        $this->referenceNumber = $referenceNumber;
    }

    /**
     * @param string $payerMobile
     */
    public function setPayerMobile(string $payerMobile): void
    {
        $this->payerMobile = $payerMobile;
    }

    /**
     * @param array|string $trustedCards
     */
    public function setTrustedCards($trustedCards): void
    {
        $trustedCards = (array)$trustedCards;
        $this->trustedCards = implode(',', $trustedCards);
    }

    /**
     * @param array $params
     * @return Gateway
     * @throws WebpayException
     */
    public static function initiatePayment(array $params): Gateway
    {
        $instance = new static($params);
        return $instance;
    }

    /**
     * Send request to gateway
     *
     * @return array|mixed
     * @throws WebpayException
     */
    public function send()
    {
        return $this->getPaymentUrl(HttpClient::sendHttpRequest(static::$apiUrl, $this->makeQueryArray()));
    }

    /**
     * @param array $response
     * @return mixed
     * @throws WebpayException
     */
    private function getPaymentUrl(array $response)
    {
        if (!array_key_exists('ok', $response)) {
            throw new WebpayException('invalid response received from gateway server');
        }

        if (!$response['ok'] === true) {
            throw new WebpayException('Webpay Api Error: ' . $response['error']);
        }

        $webpayUrl = (array)$response['result'];
        return $webpayUrl['payment_url'];
    }
}

