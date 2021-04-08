<?php

namespace Alexzvn\MoneyBooster\Web;

use pocketmine\Thread;

class AsyncServer extends Thread
{
    protected Server $web;

    protected callable $handler;

    public function __construct(Server $web, callable $handler) {
        $this->web = $web;
        $this->handler = $handler;
    }

    public function run(): void
    {
        $this->web->listen($this->handler);
    }
}
