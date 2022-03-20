<?php

namespace YG\Phalcon\Query;

final class QueryDispatcher implements QueryDispatcherInterface
{
    private array $handlers = [];

    private array $handlerClasses = [];

    private ?string $prefixQueryHandlerNamespace = null;

    /**
     * Sorgu işleyicisinin otomatik yüklenmesi için gerekli namespace eki. Sorgu işleyicisi register metodotları ile
     * kayıt edilirse gerek duyulmaz.
     *
     * @param string $prefixQueryHandlerNamespace
     */
    public function setNamespace(string $prefixQueryHandlerNamespace)
    {
        $this->prefixQueryHandlerNamespace = $prefixQueryHandlerNamespace;
    }


    public function dispatch(AbstractQuery $query)
    {
        $queryHandler = $this->getQueryHandler($query);

        if ($queryHandler == null)
            throw new \Exception('Not Found Query Handler');

        $reflection = new \ReflectionClass($query);
        $queryClassShortName = ucfirst($reflection->getShortName());

        if (method_exists($queryHandler, $queryClassShortName))
            return $queryHandler->$queryClassShortName($query);

        return $queryHandler->handle($query);
    }

    public function register(string $queryClass, string $queryHandlerClass): void
    {
        $this->handlerClasses[$queryClass] = $queryHandlerClass;
    }

    public function registerFromArray(array $handlers): void
    {
        $this->handlerClasses = array_merge($this->handlerClasses, $handlers);
    }

    /**
     * @throws \Exception
     */
    private function getQueryHandler(AbstractQuery $query): ?AbstractQueryHandler
    {
        $queryClass = get_class($query);

        if (array_key_exists($queryClass, $this->handlers))
            return $this->handlers[$queryClass];

        if (array_key_exists($queryClass, $this->handlerClasses))
        {
            $queryHandlerClass = $this->handlerClasses[$queryClass];
            $this->handlers[$queryClass] = new $queryHandlerClass;
            return $this->handlers[$queryClass];
        }

        if ($this->prefixQueryHandlerNamespace != null)
        {
            $reflection = new \ReflectionClass($query);
            $queryClassShortName = $reflection->getShortName();
            $queryHandlerClass = $this->prefixQueryHandlerNamespace . '\\' . $queryClassShortName . 'QueryHandler';

            if (class_exists($queryHandlerClass))
                return new $queryHandlerClass;

            //throw new \Exception('Not Found Query Handler(' . $queryHandlerClass . ')');
        }

        $queryClassName = get_class($query);
        $queryHandlerClassName = str_replace('Queries\\', 'QueryHandlers\\', $queryClassName) . "QueryHandler";
        if (class_exists($queryHandlerClassName))
            return new $queryHandlerClassName;

        return null;
    }
}