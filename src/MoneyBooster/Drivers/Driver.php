<?php

namespace MoneyBooster\Drivers;

use GuzzleHttp\Client;
use MoneyBooster\Contracts\BoosterDriverContract;

abstract class Driver implements BoosterDriverContract
{
    protected Client $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => $this->api()
        ]);
    }

    /**
     * return endpoint API
     *
     * @return string base uri
     */
    abstract protected function api(): string;
}
