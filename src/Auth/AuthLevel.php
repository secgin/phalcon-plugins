<?php

namespace YG\Phalcon\Auth;

abstract class AuthLevel
{
    const
        None = 0,
        Have = 1,
        Read = 3,
        Create = 5,
        Update = 7,
        Delete = 9;
}

