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
    private $reference;

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
     */
    public function __construct(array $options)
    {
        $this->setupDefaultValues($options);
    }

    private function setupDefaultValues(array $options): void
    {
        [
            'amount' ?? null => $this->amount,
            'reference' ?? null => $this->reference,
            'payer_mobile' ?? '' => $this->payerMobile,
            'cards' ?? '' => $this->trustedCards
        ] = $options;

        static::$callbackUrl = config('webpay.callback_url') ?? null;
        static::$apiKey = config('webpay.api_key') ?? null;

        $this->validateDefaultValues();
    }


    /**
     * @throws WebpayException
     */
    private function validateDefaultValues(): void
    {
        $values = [
            'amount' => $this->amount,
            'reference' => $this->reference,
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
                'callback_url' => static::$callbackUrl,
                'amount_irr' => $this->amount,
                'reference' => $this->reference,
                'payer_mobile' => $this->payerMobile,
                'trusted_pan' => $this->trustedCards
            ]
        ];
    }


    /**
     * @param array $params
     * @return Gateway
     */
    public static function initiatePayment(array $params = []): Gateway
    {
        $instance = new static($params);
        return $instance;
    }

    /**
     * Send request to gateway
     *
     * @return array|mixed
     */
    public function send()
    {
        return HttpClient::sendHttpRequest(static::$apiUrl, $this->makeQueryArray());
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @param string $reference
     */
    public function setReference(string $reference): void
    {
        $this->reference = $reference;
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
}

