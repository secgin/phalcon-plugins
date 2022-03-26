<?php

namespace YG\Test\Query\QueryHandlers;

use YG\Phalcon\Query\AbstractQueryHandler;
use YG\Test\Query\Queries\GetUser;

class UserQueryHandler extends AbstractQueryHandler
{
    public function getUser2(GetUser $query)
    {
        return 'User Data';
    }
}