<?php

namespace YG\Test\Command\CommandHandlers;

use YG\Phalcon\Command\AbstractCommandHandler;
use YG\Phalcon\IResult;
use YG\Phalcon\Result;
use YG\Test\Command\Commands\CreateUser;

class CreateUserCommandHandler extends AbstractCommandHandler
{
    public function handle(CreateUser $command): IResult
    {
        return Result::success('CreateUserCommandHandler');
    }
}