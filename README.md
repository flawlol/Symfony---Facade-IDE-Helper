# Flawlol Facade IDE Helper 
Generate IDE helper files for facades in Symfony.

## Author
- name: **Norbert Kecső**
- email: **flawlesslol123@gmail.com**


## Installation
To install the package, use Composer:
```composer require flawlol/facade-ide-helper```

## Requirements
* PHP >= 8.2
* Symfony Framework Bundle >=6.4
* flawlol/facade >=1.0

## Usage
To generate the facade helpers, run the following command:
```php bin/console app:generate-facade-helpers```

## Command
The `app:generate-facade-helpers` command generates a `_ide-helper.php` file with necessary
facade mappings.


```php
<?php

namespace App\Facade {
    class Arr
    {
        /**
         * @param array $array
         * @param string $keyPath
         * @param mixed $defaultValue
         * @return mixed
         */
        public static function get(array $array, string $keyPath, mixed $defaultValue = NULL): mixed
        {
            /** @var \App\Service\Common\Array\ArrayHelper $instance */
            return $instance->get($array, $keyPath, $defaultValue);
        }
    }
}
```

## License
This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).