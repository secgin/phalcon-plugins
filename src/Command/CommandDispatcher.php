<?php

namespace YG\Phalcon\Command;

use YG\Phalcon\IResult;
use YG\Phalcon\Result;

final class CommandDispatcher implements CommandDispatcherInterface
{

    private array $handlers = [];

    private array $handlerClasses = [];

    private ?string $prefixCommandHandlerNamespace = null;


    /**
     * Komut işleyicisinin otomatik yüklenmesi için gerekli namespace eki. Komut işleyicisi register metodotları ile
     * kayıt edilirse gerek duyulmaz.
     *
     * @param string $prefixCommandHandlerNamespace
     */
    public function setNamespace(string $prefixCommandHandlerNamespace)
    {
        $this->prefixCommandHandlerNamespace = $prefixCommandHandlerNamespace;
    }

    public function dispatch(AbstractCommand $command): IResult
    {
        try
        {
            $commandHandler = $this->getCommandHandler($command);

            if ($commandHandler == null)
                throw new \Exception('Not Found Command Handler');

            $reflection = new \ReflectionClass($command);
            $commandClassShortName = ucfirst($reflection->getShortName());

            if (method_exists($commandHandler, $commandClassShortName))
                return $commandHandler->$commandClassShortName($command);

            return $commandHandler->handle($command);
        }
        catch (\Exception $ex)
        {
            return Result::fail('Bir hata oluştu.' . $ex->getMessage());
        }
    }

    public function register(string $commandClass, string $commandHandlerClass): void
    {
        $this->handlerClasses[$commandClass] = $commandHandlerClass;
    }

    public function registerFromArray(array $handlers): void
    {
        $this->handlerClasses = array_merge($this->handlerClasses, $handlers);
    }

    private function getCommandHandler(AbstractCommand $command): ?AbstractCommandHandler
    {
        $commandClass = get_class($command);

        if (array_key_exists($commandClass, $this->handlers))
            return $this->handlers[$commandClass];

        if (array_key_exists($commandClass, $this->handlerClasses))
        {
            $commandHandlerClass = $this->handlerClasses[$commandClass];
            $this->handlers[$commandClass] = new $commandHandlerClass;
            return $this->handlers[$commandClass];
        }

        if ($this->prefixCommandHandlerNamespace != null)
        {
            $reflection = new \ReflectionClass($command);
            $commandClassShortName = $reflection->getShortName();
            $commandHandlerClass = $this->prefixCommandHandlerNamespace . '\\' . $commandClassShortName . 'CommandHandler';

            if (class_exists($commandHandlerClass))
                return new $commandHandlerClass;

            return null;
        }

        return null;
    }
}