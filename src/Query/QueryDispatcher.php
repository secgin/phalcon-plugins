<?php

namespace YG\Phalcon\Query;

use Phalcon\Di\Injectable;

final class QueryDispatcher extends Injectable implements QueryDispatcherInterface
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

        if (method_exists($queryHandler, 'handle'))
            return $queryHandler->handle($query);

        return null;
    }

    public function register(string $queryClass, string $queryHandlerClass): void
    {
        $this->handlerClasses[$queryClass] = $queryHandlerClass;
    }

    public function registerFromArray(array $handlers): void
    {
        $this->handlerClasses = array_merge($this->handlerClasses, $handlers);
    }

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

        $annotations = $this->annotations->get($queryClass);
        $classAnnotations = $annotations->getClassAnnotations();
        if ($classAnnotations->has('Handler'))
        {
            $queryHandlerClass = $classAnnotations->get('Handler')->getArgument(0);

            if (class_exists($queryHandlerClass))
            {
                $this->handlers[$queryClass] = new $queryHandlerClass;
                return $this->handlers[$queryClass];
            }
        }

        if ($this->prefixQueryHandlerNamespace != null)
        {
            $reflection = new \ReflectionClass($query);
            $queryClassShortName = $reflection->getShortName();
            $queryHandlerClass = $this->prefixQueryHandlerNamespace . '\\' . $queryClassShortName . 'QueryHandler';

            if (class_exists($queryHandlerClass))
                return new $queryHandlerClass;
        }

        $queryHandlerClassName = str_replace('Queries\\', 'QueryHandlers\\', $queryClass) . "QueryHandler";
        if (class_exists($queryHandlerClassName))
            return new $queryHandlerClassName;

        return null;
    }
}