<?php

namespace YG\Phalcon\Command;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class CommandDispatcherProvider implements ServiceProviderInterface
{
    protected string $providerName = 'commandDispatcher';

    public function register(DiInterface $di): void
    {
        $di->setShared($this->providerName, CommandDispatcher::class);
    }
}