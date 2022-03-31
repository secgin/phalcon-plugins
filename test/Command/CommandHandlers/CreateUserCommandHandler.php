<?php

namespace YG\Test\Command\CommandHandlers;

use YG\Phalcon\Command\AbstractCommandHandler;
use YG\Phalcon\ResultInterface;
use YG\Phalcon\Result;
use YG\Test\Command\Commands\CreateUser;

class CreateUserCommandHandler extends AbstractCommandHandler
{
    public function handle(CreateUser $command): ResultInterface
    {
        return Result::success('CreateUserCommandHandler');
    }
}