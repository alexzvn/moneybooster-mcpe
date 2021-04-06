<?php

namespace MoneyBooster\Card;

use MoneyBooster\Contracts\CardContract;

abstract class Card implements CardContract
{
    protected string $code;

    protected string $seri;

    public function __construct(string $code, string $seri) {
        $this->code = $code;
        $this->seri = $seri;
    }

    public function seri(): string
    {
        return $this->seri;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function telecomName()
    {
        $mapper = [
            Card::VIETTEL        => 'VIETTEL',
            Card::VINAPHONE      => 'VINAPHONE',
            Card::MOBIFONE       => 'MOBIFONE',
            Card::VIETNAM_MOBILE => 'VIETNAMMOBILE',
            Card::ZING           => 'ZING',
            Card::GARENA         => 'GARENA',
            Card::GATE           => 'GATE',
            Card::VCOIN          => 'VCOIN',
        ];

        return $mapper[$this->telecom()];
    }

    /**
     * check length of number
     *
     * @param string|int $num
     * @param int $length
     * @return bool
     */
    protected function len($num, int $length)
    {
        return !! preg_match("/^[0-9]{$length}/", $num);
    }

    abstract public function telecom(): int;

    abstract public function validate(): bool;
}

