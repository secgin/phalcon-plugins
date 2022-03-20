<?php

namespace YG\Test\Query;

use YG\Phalcon\Query\QueryDispatcher;
use YG\Test\Query\Queries\GetUser;
use YG\Test\Query\QueryHandlers\GetUserQueryHandler;

require_once '../../vendor/autoload.php';

$queryDispatcher = new QueryDispatcher();
//$queryDispatcher->setNamespace('YG\Test\Query\QueryHandlers');
//$queryDispatcher->register(GetUser::class, GetUserQueryHandler::class);

$result = $queryDispatcher->dispatch(new GetUser());

var_dump($result);