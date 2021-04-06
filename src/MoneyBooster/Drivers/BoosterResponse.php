<?php

namespace MoneyBooster\Drivers;

use MoneyBooster\Contracts\BoosterResponseContract;
use Psr\Http\Message\ResponseInterface;

abstract class BoosterResponse implements BoosterResponseContract
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response) {
        $this->response = $response;
    }
}
