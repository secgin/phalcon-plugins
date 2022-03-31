<?php

namespace YG\Phalcon\QueryBuilder;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class QueryBuilderProvider implements ServiceProviderInterface
{
    protected string $providerName = 'queryBuilder';

    public function register(DiInterface $di): void
    {
        $di->set($this->providerName, QueryBuilder::class);
    }
}