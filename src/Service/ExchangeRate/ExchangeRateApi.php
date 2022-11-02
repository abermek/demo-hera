<?php

namespace App\Service\ExchangeRate;

use App\Exception\Vendor\ApiException;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateApi
{
    private const CACHE_PREFIX = 'app.vendor.exchange_rate.';
    private const CACHE_TAGS = ['app.vendor.exchange_rate', 'app.exchange.rates'];
    private const CACHE_TTL = 86400;

    private string $accessKey;
    private HttpClientInterface $http;
    private CacheInterface $cache;

    public function __construct(string $key, HttpClientInterface $http, CacheInterface $cache)
    {
        $this->accessKey = $key;
        $this->http = $http;
        $this->cache = $cache;
    }

    public function latest(string $baseCurrencyCode): array
    {
        $cacheKey = self::CACHE_PREFIX . $baseCurrencyCode;

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($baseCurrencyCode) {
            $item
                ->expiresAfter(self::CACHE_TTL)
                ->tag(self::CACHE_TAGS);

            $url = 'https://api.apilayer.com/exchangerates_data/latest?base='. $baseCurrencyCode;
            $headers = ['apikey' => $this->accessKey];

            try {
                $response = $this->http->request('GET', $url, ['headers' => $headers]);
                $content = @json_decode($response->getContent(), true);

                if (!$content['success']) {
                    throw new ApiException($url, $content['error']['info'], $content['error']['code']);
                }

                return $content['rates'];

            } catch (TransportExceptionInterface | ClientException $e) {
                throw new ApiException($url, $e->getCode(), $e->getMessage());
            }
        });
    }
}