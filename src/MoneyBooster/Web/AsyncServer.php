<?php

namespace MoneyBooster\Web;

use pocketmine\Thread;

class AsyncServer extends Thread
{
    protected Server $web;

    protected \Closure $handler;

    public function __construct(Server $web, \Closure $handler) {
        $this->web = $web;
        $this->handler = $handler;
    }

    public function run(): void
    {
        $this->web->listen($this->handler);
    }
}
