<?php

namespace YG\Test\Command\Commands;

use YG\Phalcon\Command\AbstractCommand;

/**
 * @Handler('YG\Test\Command\CommandHandlers\UserCommandHandler')
 *
 * @property string $name
 * @property string $email
 */
class CreateUser extends AbstractCommand
{
    protected string $name;

    protected string $email;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }
}