<?php

namespace MoneyBooster\Contracts;

interface CardContract
{
    public const VIETTEL = 1;

    public const VINAPHONE = 2;

    public const MOBIFONE = 3;

    public const VIETNAM_MOBILE = 4;

    public const ZING = 5;

    public const GATE = 6;

    public const GARENA = 7;

    public const VCOIN = 8;

    public function seri(): string;

    public function code(): string;

    public function telecom(): int;

    public function validate(): bool;
}
