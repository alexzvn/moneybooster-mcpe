<?php

namespace Alexzvn\MoneyBooster\Drivers\Cardvip;

use Alexzvn\MoneyBooster\Drivers\BoosterResponse;

class CardvipResponse extends BoosterResponse
{
    public function status(): int
    {
        switch ($this->data->status) {
            case 200: return static::SUCCESS;
            case 400: return static::DUPLICATED;
            case 401: return static::INVALID;
            default:  return static::UNKNOWN;
        }
    }

    public function success(): bool
    {
        return $this->status() === static::SUCCESS;
    }

    public function isError(): bool
    {
        return ! $this->success();
    }
}

