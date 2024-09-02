<?php

namespace Blackbird\DTOToolkit\Trait;

use ReflectionException;

trait DtoFactoryTrait
{
    /**
     * @throws ReflectionException
     */
    public static function create(array $values): self
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            throw new \RuntimeException("Class " . static::class . " does not have a constructor.");
        }

        $parameters = $constructor->getParameters();
        $args = [];

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();

            if (!array_key_exists($name, $values)) {
                if ($parameter->isDefaultValueAvailable()) {
                    $args[] = $parameter->getDefaultValue();
                } else {
                    throw new \InvalidArgumentException("Missing required key: $name");
                }
            } else {
                $args[] = $values[$name];
            }
        }

        return $reflectionClass->newInstanceArgs($args);
    }
}
