<?php

namespace YG\Phalcon\Query;

interface QueryDispatcherInterface
{
    /**
     * Sorgu çalıştırıcı
     *
     * @param AbstractQuery $query
     *
     * @return mixed
     */
    public function dispatch(AbstractQuery $query);

    /**
     * @param string $queryClass        Sorgu class adı namespace ile birlikte
     * @param string $queryHandlerClass Sorgu işletyicisi class adı namespace ile birlikte
     */
    public function register(string $queryClass, string $queryHandlerClass): void;

    /**
     * Birden fazla sorgu işleyicisini kaydetmek için dizi alır.
     *
     * Dizi örneği:
     * [
     *  YG\Query\GetUser => YG\Query\Handler\GetUserQueryHandler,
     *  YG\Query\GetUserList => YG\Query\GetUserListQueryHandler,
     * ...
     * ]
     *
     * @param array $handlers
     */
    public function registerFromArray(array $handlers): void;
}