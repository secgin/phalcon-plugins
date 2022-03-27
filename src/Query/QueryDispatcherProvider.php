<?php

namespace YG\Phalcon\Query;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class QueryDispatcherProvider implements ServiceProviderInterface
{
    protected string $providerName = 'queryDispatcher';

    public function register(DiInterface $di): void
    {
        $di->setShared('queryDispatcher', QueryDispatcher::class);
    }
}