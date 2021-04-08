<?php

namespace Alexzvn\MoneyBooster\Drivers;

use Alexzvn\MoneyBooster\Contracts\BoosterCallbackContract;
use Alexzvn\MoneyBooster\Web\Parser\Request;

abstract class BoosterCallback implements BoosterCallbackContract
{
    protected Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->boot($request);
    }

    abstract protected function boot(Request $request): void;
}
