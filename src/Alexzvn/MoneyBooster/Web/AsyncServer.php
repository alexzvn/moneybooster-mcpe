<?php

namespace Alexzvn\MoneyBooster\Web;

use pocketmine\Thread;

class AsyncServer extends Thread
{
    protected WebServer $web;

    protected $handler;

    public function __construct(WebServer $web, $handler) {
        $this->web = $web;
        $this->handler = $handler;
    }

    public function run(): void
    {
        $this->web->listen($this->handler);
    }
}
