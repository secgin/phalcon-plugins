<?php

use Phalcon\Di\FactoryDefault;
use YG\Phalcon\Query\QueryDispatcher;
use YG\Phalcon\Query\QueryDispatcherProvider;
use YG\Test\Query\Queries\GetUser;
use YG\Test\Query\QueryHandlers\GetUserQueryHandler;

require_once '../../vendor/autoload.php';

$container = new FactoryDefault();
$container->register(new QueryDispatcherProvider());

$queryDispatcher = $container->get('queryDispatcher');
//$queryDispatcher->setNamespace('YG\Test\Query\QueryHandlers');
//$queryDispatcher->register(GetUser::class, GetUserQueryHandler::class);

$result = $queryDispatcher->dispatch(new GetUser());

var_dump($result);