<?php

namespace YG\Test\Query\QueryHandlers;

use YG\Phalcon\Query\AbstractQueryHandler;
use YG\Test\Query\Queries\GetUser;

class GetUserQueryHandler extends AbstractQueryHandler
{
    public function handle(GetUser $query)
    {
        return 'User';
    }
}