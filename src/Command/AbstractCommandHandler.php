<?php

namespace YG\Phalcon\Command;

use Phalcon\Di\Injectable;
use Phalcon\Mvc\ModelInterface;
use YG\Phalcon\ResultInterface;

/**
 * @method ResultInterface handle(AbstractCommand $command)
 */
abstract class AbstractCommandHandler extends Injectable
{
    final protected function modelToMessageText(ModelInterface $model): string
    {
        $pageMessage = "";
        foreach ($model->getMessages() as $message)
        {
            if (!empty($pageMessage))
                $pageMessage .= PHP_EOL;
            $pageMessage .= $message;
        }
        return $pageMessage;
    }
}