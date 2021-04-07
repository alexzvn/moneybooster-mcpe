<?php

namespace Alexzvn\MoneyBooster\Drivers;

use Alexzvn\MoneyBooster\Contracts\BoosterResponseContract;
use Psr\Http\Message\ResponseInterface;

abstract class BoosterResponse implements BoosterResponseContract
{
    protected ResponseInterface $response;

    /**
     * data after decode
     *
     * @var \stdClass
     */
    protected $data;

    public function __construct(ResponseInterface $response) {
        $this->response = $response;
        $this->decode();
    }

    public function decode(): void
    {
        $this->data = json_decode((string) $this->response->getBody());
    }
}
