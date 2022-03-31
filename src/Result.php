<?php

namespace YG\Phalcon;

final class Result implements ResultInterface
{
    private
        $success,
        $message,
        $data;

    private function __construct($success, $message, $data)
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }

    static public function success($data = null, $message = null)
    {
        return new Result(true, $message, $data);
    }

    static public function fail($message, $data = null)
    {
        return new Result(false, $message, $data);
    }

    public function __get($name)
    {
        if (property_exists($this, $name))
            return $this->$name;
        else
        {
            if (is_array($this->data) and isset($this->data[$name]))
                return $this->data[$name];
            elseif (isset($this->data->$name))
                return $this->data->$name;
        }

        return null;
    }

    #region ResultInterface
    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function isFail(): bool
    {
        return !$this->success;
    }

    public function getMessage(): string
    {
        return $this->message == null ? '' : $this->message;
    }

    public function getData()
    {
        return $this->data;
    }
    #endregion
}