<?php

namespace YG\Phalcon\Command;

use Phalcon\Annotations\Collection;
use Phalcon\Di\Injectable;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;
use YG\Phalcon\ResultInterface;
use YG\Phalcon\Result;

final class CommandDispatcher extends Injectable implements CommandDispatcherInterface, EventsAwareInterface
{

    private array $handlers = [];

    private array $handlerClasses = [];

    private ?string $prefixCommandHandlerNamespace = null;

    /**
     * @var ManagerInterface
     */
    protected $eventsManager;


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

    public function dispatch(AbstractCommand $command): ResultInterface
    {
        try
        {
            $commandHandler = $this->getCommandHandler($command);

            if ($commandHandler == null)
                throw new \Exception('Not Found Command Handler');

            $reflection = new \ReflectionClass($command);
            $commandClassShortName = ucfirst($reflection->getShortName());
            if (method_exists($commandHandler, $commandClassShortName))
                $result = $commandHandler->$commandClassShortName($command);
            else
                $result = $commandHandler->handle($command);

            if ($this->eventsManager and $result->isSuccess())
                $this->eventsManager->fire('commandDispatcher:afterDispatch', $this, [
                    'command' => $command,
                    'result' => $result->getData()]);

            return $result;
        }
        catch (\Exception $ex)
        {
            return Result::fail($ex->getMessage());
        }
        catch (\Error $error)
        {
            return Result::fail($error->getMessage());
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

        $annotations = $this->annotations->get($commandClass);
        $classAnnotations = $annotations->getClassAnnotations();
        if ($classAnnotations instanceof Collection and $classAnnotations->has('Handler') === true)
        {
            $commandHandlerClass = $classAnnotations->get('Handler')->getArgument(0);

            if (class_exists($commandHandlerClass))
            {
                $this->handlers[$commandClass] = new $commandHandlerClass;
                return $this->handlers[$commandClass];
            }
        }

        if ($this->prefixCommandHandlerNamespace != null)
        {
            $reflection = new \ReflectionClass($command);
            $commandClassShortName = $reflection->getShortName();
            $commandHandlerClass = $this->prefixCommandHandlerNamespace . '\\' . $commandClassShortName . 'CommandHandler';

            if (class_exists($commandHandlerClass))
                return new $commandHandlerClass;
        }

        $commandHandlerClassName = str_replace('Commands\\', 'CommandHandlers\\', $commandClass) . "CommandHandler";
        if (class_exists($commandHandlerClassName))
            return new $commandHandlerClassName;

        return null;
    }

    public function getEventsManager(): ?ManagerInterface
    {
        return $this->eventsManager;
    }

    public function setEventsManager(ManagerInterface $eventsManager): void
    {
        $this->eventsManager = $eventsManager;
    }
}