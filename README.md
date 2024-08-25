# Symfony - Facade

## Author
- name: **Norbert Kecső**
- email: **flawlesslol123@gmail.com**

## Overview

This project provides a facade implementation for Symfony applications. The facade pattern is used to provide a simplified interface to a complex subsystem. In this case, the facade interacts with a container to manage dependencies.

## Installation

To install the package, use Composer:

```bash
composer require flawlol/facade
```

## Usage
Defining a Facade

To define a facade, create a class that extends the `Facade` abstract class and implements the `getFacadeAccessor` method. This method should return the name of the service in the container that the facade will interact with.

```php
<?php

namespace App\Facade;

use Flawlol\Facade\Abstract\Facade;

class MyFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'my_service';
    }
}
```

## Using the Facade
Once the facade is defined, you can use it to call methods on the underlying service.

```php
use App\Facade\MyFacade;

$result = MyFacade::someMethod($arg1, $arg2);
```

## Setting the Container
The container is automatically set during the bundle boot process. Ensure that your bundle extends `FacadeBundle`.
    
```php
<?php

namespace Flawlol\Facade;

use Flawlol\Facade\Abstract\Facade;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FacadeBundle extends Bundle
{
    public function boot(): void
    {
        parent::boot();

        $container = $this->container;

        Facade::setContainer($container);
    }
}
```

## Exceptions
The package defines the following exceptions:

- `ContainerIsAlreadySetException`: Thrown when attempting to set the container more than once.
- `ContainerIsNotSetException`: Thrown when attempting to use the facade without setting the container.

## IDE Helper
The `_idehelper.php` file provides helper classes to improve IDE autocompletion and static analysis. These helpers act as proxies to the actual service methods, making it easier to work with facades in your IDE.

### Example
The following example demonstrates how to use the `Arr` facade to access array elements using a key path.

```php
<?php

namespace App\Facade {
    class Arr
    {
        /**
         * Get a value from an array using a key path.
         *
         * @param array $array The array to search.
         * @param string $keyPath The key path to search for.
         * @param mixed $defaultValue The default value to return if the key path is not found.
         * @return mixed The value found at the key path or the default value.
         */
        public static function get(array $array, string $keyPath, mixed $defaultValue = null)
        {
            /** @var \App\Service\Common\Array\ArrayHelper $instance */
            return $instance->get($array, $keyPath, $defaultValue);
        }
    }
}
```
- Namespace: `App\Facade`
- Class: `Arr`
- Method: `get(array $array, string $keyPath, mixed $defaultValue = null)`

The `Arr` class provides a static method `get` to retrieve values from an array using a key path. 
This method acts as a proxy to the `get` method of the `ArrayHelper` service, allowing you to use the facade for cleaner and more readable code.


## License
This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
