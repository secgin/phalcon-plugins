<?php

namespace YG\Test\Query;

use Phalcon\Di\FactoryDefault;
use YG\Phalcon\Query\QueryDispatcher;
use YG\Test\Query\Queries\GetUser;
use YG\Test\Query\QueryHandlers\GetUserQueryHandler;

require_once '../../vendor/autoload.php';

$container = new FactoryDefault();

$queryDispatcher = new QueryDispatcher();
$queryDispatcher->setDI($container);
//$queryDispatcher->setNamespace('YG\Test\Query\QueryHandlers');
//$queryDispatcher->register(GetUser::class, GetUserQueryHandler::class);

$result = $queryDispatcher->dispatch(new GetUser());

var_dump($result);