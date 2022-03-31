<?php

namespace YG\Phalcon\Auth;

interface DataServiceInterface
{
    public function getUser(int $userId): ?array;

    public function hasPermission(string $authCode, int $authLevel, ?string $module): bool;
}