<?php

namespace YG\Phalcon\Command;

use YG\Phalcon\ResultInterface;

interface CommandDispatcherInterface
{
    /**
     * Komut çalıştırıcı
     *
     * @param AbstractCommand $command
     *
     * @return ResultInterface
     */
    public function dispatch(AbstractCommand $command): ResultInterface;

    /**
     * @param string $commandClass        Komut class adı namespace ile birlikte
     * @param string $commandHandlerClass Komut işletyicisi class adı namespace ile birlikte
     */
    public function register(string $commandClass, string $commandHandlerClass): void;

    /**
     * Birden fazla komut işleyicisini kaydetmek için dizi alır.
     *
     * Dizi örneği:
     * [
     *  CreateUser => CreateUserCommandHandler,
     *  UpdateRole => UpdateRuleCommandHandler,
     * ...
     * ]
     *
     * @param array $handlers
     */
    public function registerFromArray(array $handlers): void;
}