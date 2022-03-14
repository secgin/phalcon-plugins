<?php

namespace YG\Phalcon;

interface IResult
{
    public function isSuccess(): bool;

    public function isFail(): bool;

    public function getMessage(): string;

    public function getData();
}