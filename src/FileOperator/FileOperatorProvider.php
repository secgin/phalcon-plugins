<?php

namespace YG\Phalcon\FileOperator;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class FileOperatorProvider implements ServiceProviderInterface
{
    protected string $providerName = 'fileOperator';

    public function register(DiInterface $di): void
    {
        $di->setShared($this->providerName, FileOperator::class);
    }
}