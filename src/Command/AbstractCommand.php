<?php

namespace YG\Phalcon\Command;

use ReflectionException;

abstract class AbstractCommand
{
    /**
     * @throws ReflectionException
     */
    static public function create(array $data): self
    {
        $command = self::newInstance($data);
        $command->assign($data);

        return $command;
    }

    /**
     * @param array $data
     *
     * @return AbstractCommand
     * @throws ReflectionException
     */
    static private function newInstance(array $data): object
    {
        $reflection = new \ReflectionClass(get_called_class());
        $constructorMethod = $reflection->getConstructor();
        $parameters = $constructorMethod->getParameters();

        $args = [];
        foreach ($parameters as $parameter)
        {
            $args[$parameter->name] = $data[$parameter->name] ?? null;
        }

        return $reflection->newInstanceArgs($args);
    }


    public function assign(array $data): self
    {
        $reflection = new \ReflectionClass($this);

        foreach ($data as $name => $value)
        {
            if ($reflection->hasProperty($name))
            {
                $property = $reflection->getProperty($name);

                if ($property->isPublic())
                {
                    $this->$name = $value;
                }
            }
        }
        return $this;
    }

    public function getData(): array
    {
        $columnMap = null;
        if (method_exists($this, 'getColumnMap'))
            $columnMap = $this->getColumnMap();

        $reflection = new \ReflectionClass($this);

        $data = [];
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED) as $property)
        {
            $propertyName = $property->getName();
            $propertyValue = null;

            if (!isset($this->$propertyName))
            {
                $propertyType = $property->getType();
                if ($propertyType != null)
                {
                    switch ($propertyType->getName())
                    {
                        case 'bool':
                            $propertyValue = 0;
                            break;
                        default:
                            $propertyValue = null;
                    }
                }
            }
            else
            {
                $propertyValue = $this->$propertyName;

                $propertyType = $property->getType();
                if ($propertyType != null and $propertyType->getName() == 'bool')
                    $propertyValue = (int)$propertyValue;
            }


            $fieldName = ($columnMap != null and array_key_exists($propertyName, $columnMap))
                ? $columnMap[$propertyName]
                : $propertyName;

            $data[$fieldName] = $propertyValue;
        }
        return $data;
    }

    public function __get($name)
    {
        if (property_exists($this, $name))
            return $this->$name;

        return null;
    }
}