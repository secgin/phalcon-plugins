<?php

namespace YG\Phalcon\FileOperator;

/**
 * @property string|null $fileName
 */
final class ResultMove
{
    private bool $success;

    protected ?string $fileName;

    private function __construct(bool $success, ?string $fileName = null)
    {
        $this->success = $success;
        $this->fileName = $fileName;
    }

    public static function success(?string $fileName): ResultMove
    {
        return new ResultMove(true, $fileName);
    }

    public static function fail(): ResultMove
    {
        return new ResultMove(false);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function isFail(): bool
    {
        return !$this->success;
    }

    public function __get($name)
    {
        if (property_exists($this, $name))
            return $this->$name;

        return null;
    }
}