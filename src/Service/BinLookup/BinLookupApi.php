<?php

namespace App\Service\BinLookup;

use App\Contract\BINInterface;
use App\DTO\BIN;
use App\Exception\Vendor\ApiException;
use App\Validator\Json\Bin as AssertBIN;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BinLookupApi
{
    private const CACHE_PREFIX = 'app.vendor.binlist.';
    private const CACHE_TAGS = ['app.vendor.binlist', 'app.bin'];
    private const CACHE_TTL = 86400;

    private HttpClientInterface $http;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;
    private CacheInterface $cache;

    public function __construct(HttpClientInterface $http, ValidatorInterface $validator, SerializerInterface $serializer, CacheInterface $cache)
    {
        $this->http = $http;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->cache = $cache;
    }

    public function lookup(string $bin): BINInterface
    {
        $cacheKey = self::CACHE_PREFIX . $bin;

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($bin) {
            $item
                ->expiresAfter(self::CACHE_TTL)
                ->tag(self::CACHE_TAGS);

            $url = "https://lookup.binlist.net/$bin";

            try {
                $response = $this->http->request('GET', $url);

                $content = $response->getContent();

                $constraints = new Assert\Sequentially([new Assert\Json(), new AssertBIN()]);
                $violations = $this->validator->validate($content, $constraints);

                if ($violations->count() > 0) {
                    throw new ApiException($url, "The response contains unexpected JSON", 500);
                }

                return $this->serializer->deserialize($content, BIN::class, 'json');

            } catch (TransportExceptionInterface | ClientException $e) {
                throw new ApiException($url, $e->getMessage(), $e->getCode(), $e);
            }
        });
    }
}