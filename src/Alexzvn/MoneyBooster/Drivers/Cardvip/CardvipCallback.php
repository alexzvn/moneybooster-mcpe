<?php

namespace Alexzvn\MoneyBooster\Drivers\Cardvip;

use Alexzvn\MoneyBooster\Drivers\BoosterCallback;
use sekjun9878\RequestParser\Request;

class CardvipCallback extends BoosterCallback
{
    /**
     * Callback data
     *
     * @var \stdClass
     */
    protected $data;

    protected function boot(Request $request): void
    {
        $this->data = (object) $request->getGET();
    }

    public function player(): string
    {
        return $this->data->requestid;
    }

    public function status(): int
    {
        switch ($this->data->status) {
            case 200: return static::SUCCESS;
            case 201: return static::WRONG_AMOUNT;
            default: return static::CARD_INVALID;
        }
    }

    public function amount(): int
    {
        return $this->data->value_receive;
    }

    public function success(): bool
    {
        return in_array($this->status(), [200, 201], true);
    }
}
