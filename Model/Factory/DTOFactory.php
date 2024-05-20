<?php

declare(strict_types=1);

namespace Blackbird\DTOToolkit\Model\Factory;

use Blackbird\DTOToolkit\Exception\ClassNotFound;
use Blackbird\DTOToolkit\Exception\InvalidPropertyOrMethod;
use Magento\Framework\Exception\LocalizedException;

class DTOFactory
{
    public function create(string $dtoClassName ,array $values = []): object
    {
        $dto = \Magento\Framework\App\ObjectManager::getInstance()->create($dtoClassName);

        if (!empty($values)) {
            $this->hydrate($dto ,$values);
        }

        return $dto;
    }

    protected function hydrate(object $dto, array $values): void
    {
        foreach ($values as $key => $value) {
            $formattedKey = $this->snakeCaseToCamelCase($key);

            if (!\property_exists($dto, $formattedKey)) {
                continue;
            }

            if (\is_array($value)) {
                $reflectionProperty = new \ReflectionProperty($dto::class, $formattedKey);
                $propertyType = $reflectionProperty->getType()->getName();

                if ($propertyType === 'array') {
                    $this->handleArrayType($dto, $reflectionProperty, $formattedKey, $value);
                    continue;
                }

                if (\class_exists($propertyType)) {
                    $this->assignDtoProperty(
                        $dto,
                        $formattedKey,
                        $this->handleObjectType($propertyType, $value)
                    );
                    continue;
                }
            }

            $this->assignDtoProperty($dto, $formattedKey, $value);
        }
    }

    protected function handleArrayType(
        object $dto,
        \ReflectionProperty $reflectionProperty,
        string $formattedKey,
        array $value
    ): void
    {
        $docComment = (string)$reflectionProperty->getDocComment();
        \preg_match('/@arrayType\(class="([^"]+)"\)/', $docComment, $matches);

        if (empty($docComment) || empty($matches[1])) {
            $this->assignDtoProperty($dto, $formattedKey, $value);
            return;
        }

        foreach ($value as $item) {
            $this->addDtoProperty($dto, $formattedKey, $this->handleObjectType($matches[1], $item));
        }
    }

    protected function handleObjectType(string $class, array $value): object
    {
        if (!\class_exists($class)) {
            throw new ClassNotFound(__("Class %1 does not exist or does not have a create method", $class));
        }

        return $this->create($class, $value);
    }

    protected function assignDtoProperty(object $dto, string $property, mixed $value): void
    {
        if ($value === null) {
            return;
        }

        $setter = 'set' . \ucfirst($property);
        $dto->$setter($value);
    }

    protected function addDtoProperty(object $dto, string $property, mixed $value): void
    {
        if ($value === null) {
            return;
        }

        $setter = 'set' . \ucfirst($property);
        $getter = 'get' . \ucfirst($property);

        $currentValues = $dto->$getter();
        $currentValues[] = $value;
        $dto->$setter($currentValues);
    }

    protected function snakeCaseToCamelCase(string $snakeCase): string
    {
        return \lcfirst(\str_replace('_', '', \ucwords($snakeCase, '_')));
    }
}
