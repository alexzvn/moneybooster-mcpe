<?php

namespace MoneyBooster\Card;

use MoneyBooster\Contracts\CardContract;
use Reflection;
use ReflectionClass;
use ReflectionClassConstant;

/**
 * @var int $amount
 * @var string $code
 * @var string $seri
 */
abstract class Card implements CardContract
{
    protected string $code;

    protected string $seri;

    protected int $amount;

    protected array $acceptAmounts;

    public function __construct(string $code, string $seri, int $amount) {
        $this->code   = $code;
        $this->seri   = $seri;
        $this->amount = $amount;
        $this->acceptAmounts = $this->getAcceptAmounts();
    }

    public function seri(): string
    {
        return $this->seri;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function telecomName()
    {
        $mapper = [
            Card::VIETTEL        => 'VIETTEL',
            Card::VINAPHONE      => 'VINAPHONE',
            Card::MOBIFONE       => 'MOBIFONE',
            Card::VIETNAMMOBILE => 'VIETNAMMOBILE',
            Card::ZING           => 'ZING',
            Card::GARENA         => 'GARENA',
            Card::GATE           => 'GATE',
            Card::VCOIN          => 'VCOIN',
        ];

        return $mapper[$this->telecom()];
    }

    /**
     * get allowed amount
     *
     * @return int[]
     */
    protected function getAcceptAmounts(): array
    {
        return [
            10_000,
            20_000,
            50_000,
            100_000,
            200_000,
            500_000
        ];
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

    public function telecom(): int
    {
        $telecom = explode('\\', static::class);
        $telecom = strtoupper(array_pop($telecom));

        $instance = new ReflectionClass($this);

        return $instance->getConstant($telecom);
    }

    public function validate(): bool
    {
        return $this->validateAmount() && $this->validateNumber();
    }

    public function validateAmount()
    {
        return in_array($this->amount, $this->acceptAmounts);
    }

    /**
     * Validate serial and pin code
     *
     * @return bool
     */
    abstract public function validateNumber(): bool;

    public function __get(string $key)
    {
        return $this->$key ?? null;
    }
}

