<?php

namespace YG\Phalcon\Auth;

use Phalcon\Di\Injectable;

/**
 * @property DataServiceInterface $authDataService
 */
final class Auth extends Injectable implements AuthInterface
{
    private $userInfo = [];
    private $authName;

    public function __construct($authName)
    {
        $this->authName = $authName;

        $auth = $this->session->get($this->authName);
        if ($auth)
            $this->userInfo = $auth;
    }

    public function login(array $userInfo, bool $remember = false): void
    {
        $this->userInfo = $userInfo;

        if ($remember)
            $this->createRememberEnvironment();

        $this->registerSession();
    }

    public function getIdentity(): string
    {
        return $this->userInfo['id'] ?? '';
    }

    public function getName(): string
    {
        return $this->userInfo['name'] ?? '';
    }

    public function logout(): void
    {
        if ($this->cookies->has('RMU'))
            $this->cookies->get('RMU')->delete();

        if ($this->cookies->has('RMT'))
            $this->cookies->get('RMT')->delete();

        $this->session->remove($this->authName);
    }

    public function isLogin(): bool
    {
        return $this->session->has($this->authName);
    }

    public function hasRememberMe(): bool
    {
        return $this->cookies->has('RMU');
    }

    public function loginWithRememberMe(): bool
    {
        if (!$this->getDI()->has('authDataService'))
            return false;

        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = $this->authDataService->getUser((int)$userId);
        if ($user)
        {
            $userAgent = $this->request->getUserAgent();
            $token = md5($user['email'], $userAgent);

            if ($cookieToken == $token)
            {
                $this->userInfo = $this->authDataService->getUser($userId);
                $this->registerSession();
                return true;
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return false;
    }

    public function hasPermission(string $authCode, int $authLevel, ?string $module = null): bool
    {
        return $this->authDataService->hasPermission($authCode, $authLevel, $module);
    }

    public function __get($propertyName)
    {
        if (array_key_exists($propertyName, $this->userInfo))
            return $this->userInfo[$propertyName];

        return parent::__get($propertyName);
    }

    private function registerSession()
    {
        $this->session->set($this->authName, $this->userInfo);
    }

    private function createRememberEnvironment()
    {
        $userId = $this->getIdentity();
        $userEmail = $this->userInfo['email'];

        $userAgent = $this->request->getUserAgent();
        $token = md5($userEmail, $userAgent);

        $expire = time() + 8 * 86400;

        $this->cookies->set('RMU', $userId, $expire);
        $this->cookies->set('RMT', $token, $expire);
        $this->cookies->send();
    }

}