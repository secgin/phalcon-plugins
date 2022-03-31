<?php

namespace YG\Phalcon\Auth;

use Phalcon\Config;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class AuthProvider implements ServiceProviderInterface
{
    protected string $provideName = 'auth';

    public function register(DiInterface $di): void
    {
        /** @var Config $config */
        $config = $di->get('config');
        $authName = $config->path('auth.name', 'auth') ?? 'auth';

        $di->setShared($this->provideName, function() use ($authName) {
            return new Auth($authName);
        });
    }
}