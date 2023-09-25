<?php

declare(strict_types=1);

namespace App\Client;

class BinClient implements SimpleBinClient
{

    private string $url = "https://lookup.binlist.net/";

    public function __construct(array $options = [])
    {
        if (isset($options['url']) && trim($options['url'])) {
            $this->url = trim($options['url']);
        }
    }

    /**
     * @throws \JsonException
     */
    public function getById(int $id): object
    {
        return $this->sendRequest($id);
    }

    /**
     * @param  mixed  $target
     * @param  array  $params
     *
     * @return string
     */

    protected function getApiUrl($target, array $params = []): string
    {
        $baseMethodUrl = rtrim($this->url, "/")."/".$target;

        return count($params) ? $baseMethodUrl."?".http_build_query($params) : $baseMethodUrl;
    }

    /**
     * @param  mixed  $target
     *
     * @throws \JsonException
     */
    protected function sendRequest($target): object
    {
        $content = file_get_contents($this->getApiUrl($target));
        return json_decode(
            file_get_contents($content),
            false,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}