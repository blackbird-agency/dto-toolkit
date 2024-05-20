<!-- markdownlint-configure-file {
  "MD013": {
    "code_blocks": false,
    "tables": false
  },
  "MD033": false,
  "MD041": false
} -->

<div align="center">
  
# dto-toolkit

[![Latest Stable Version](https://img.shields.io/badge/version-1.0.0-blue)](https://packagist.org/packages/blackbird/dto-toolkit)
[![Total Downloads](https://img.shields.io/packagist/dt/blackbird/dto-toolkit)](https://packagist.org/packages/blackbird/dto-toolkit)
[![License: MIT](https://img.shields.io/github/license/blackbird-agency/dto-toolkit.svg)](./LICENSE)


The Magento 2 DTO Toolkit Module provides the tools for creating Data Transfer Objects (DTO) in Magento 2 while retaining Magento's key features such as plugins, preferences, etc.

This module aims to enhance the development experience by offering a structured approach to handling data transfer within Magento applications.

[Features](#features) •
[Installation](#installation) •
[Usage](#usage) •
[More modules](#more-modules)

</div>

## Features

- **Easy DTO Creation:** Simplifies the process of creating and managing DTOs.
- **Magento Integration:** Maintains full compatibility with Magento's DI system, plugins, and preferences.
- **Auto Hydration:** Provides a way to automatically hydrate your DTO with an array.

## Installation

> ### Requirements
> - PHP >= 7.4

```
composer require blackbird/dto-toolkit
```
```
php bin/magento setup:upgrade
```
*In production mode, do not forget to recompile and redeploy the static resources.*

## Usage

To instanciate your DTO while maintains full compatibility with Magento 2, please use the provided DTOFactory
```php
use Blackbird\DTOToolkit\Model\Factory\DTOFactory;

/** @var DTOFactory $dtoFactory **/
protected $dtoFactory;

public function __construct(
    DTOFactory $dtoFactory
) {
  $this->dtoFactory = $dtoFactory;
}

[...]

$myDtoInstance = $this->dtoFactory->create(MyDTO::class);
```

To automatically hydrate your DTO instance with an array
```php
use Blackbird\DTOToolkit\Model\Factory\DTOFactory;

/** @var DTOFactory $dtoFactory **/
protected $dtoFactory;

public function __construct(
    DTOFactory $dtoFactory
) {
  $this->dtoFactory = $dtoFactory;
}

[...]

myArray = [
  'key_one' => 1,
  'key_two' => 2
];

$myDtoInstance = $this->dtoFactory->create(MyDTO::class, $myArray);
```
Warning, if your DTO class doesn't have the properties `keyOne` or `keyTwo`, they will not be hydrated.
