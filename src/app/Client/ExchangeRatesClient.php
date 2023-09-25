<?php

declare(strict_types=1);

namespace App\Client;

class ExchangeRatesClient implements SimpleRatesClient
{
    private string $url = "https://api.exchangeratesapi.io/";

    public function __construct(array $options = [])
    {
        if (isset($options['url']) && trim($options['url'])) {
            $this->url = trim($options['url']);
        }
    }

    /**
     * @throws \JsonException
     */
    public function getAll(): array
    {
        return $this->sendRequest('latest');
    }

    protected function getApiUrl(string $method, array $params = []): string
    {
        $baseMethodUrl = rtrim($this->url, "/")."/".$method;

        return count($params) ? $baseMethodUrl."?".http_build_query($params) : $baseMethodUrl;
    }

    /**
     * @throws \JsonException
     */
    protected function sendRequest(string $method): array
    {
        $content = file_get_contents($this->getApiUrl($method));
        return json_decode(
            $content,
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}