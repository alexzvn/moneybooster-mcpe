<?php

namespace Alexzvn\MoneyBooster\Drivers;

use Alexzvn\MoneyBooster\Contracts\BoosterResponseContract;

abstract class BoosterResponse implements BoosterResponseContract
{
    protected string $response;

    /**
     * data after decode
     *
     * @var \stdClass
     */
    protected $data;

    public function __construct(string $response) {
        $this->response = $response;
        $this->decode();
    }

    public function decode(): void
    {
        $this->data = json_decode($this->response);
    }
}
