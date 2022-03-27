<?php

use Phalcon\Di\FactoryDefault;
use YG\Phalcon\Command\CommandDispatcher;
use YG\Phalcon\Command\CommandDispatcherProvider;
use YG\Test\Command\CommandHandlers\UserCommandHandler;
use YG\Test\Command\Commands\CreateUser;

require_once '../../vendor/autoload.php';

$container = new FactoryDefault();
$container->register(new CommandDispatcherProvider());

$commandDispatcher = $container->get('commandDispatcher');
//$commandDispatcher->setNamespace('YG\Test\Command\CommandHandlers');
//$commandDispatcher->register(CreateUser::class, UserCommandHandler::class);

$result = $commandDispatcher->dispatch(new CreateUser('Seçgin', 'secginsanli@gmail.com'));

var_dump($result);