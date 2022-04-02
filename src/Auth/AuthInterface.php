<?php
namespace YG\Phalcon\Auth;

interface AuthInterface
{
    public function login(array $userInfo, bool $remember = false): void;

    public function getIdentity(): string;

    public function getName(): string;

    public function logout(): void;

    public function isLogin(): bool;

    public function hasRememberMe(): bool;

    public function loginWithRememberMe(): bool;

    public function hasPermission(string $authCode, int $authLevel, ?string $module = null): bool;

    public function hasPermissionFromAnnotation(string $className, string $methodName, string $moduleName): bool;
}

