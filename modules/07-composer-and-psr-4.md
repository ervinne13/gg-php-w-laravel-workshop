# Composer & PSR 4

## About Composer

Composer is a tool for dependency management in PHP. It allows you to declare the libraries your project depends on and it will manage (install/update) them for you

## Initializing Composer in Your Project

Key in the command:

```bash
composer init
```

... and answer the questions (follow along the instructor as a guide.)

With this, we'll now be able to define our dependencies and use PSR-0 or PSR-4.

## Using Composer to Autoload Files

Primarily, we'll use PSR-4 to autoload our components. For our helpers however, we'll let composer autoload them as is instead of using namespaces as PSR-4 dictates since they only contain functions.

Update your `composer.json` file and add the following inside the json object:
```json
"autoload": {
    "files": [
        "src/helpers/string_helper_functions.php",
        "src/helpers/url_helper_functions.php",
        "src/helpers/view_loader_functions.php"
    ]
}
```

The whole `composer.json` should look something like this:

```json
{
    "name": "ervinne/gg-byo1-bs-website",
    "description": "My Bootstrap Website",
    "authors": [
        {
            "name": "Ervinnne Sodusta",
            "email": "ervinnesodusta13@yahoo.com.ph"
        }
    ],
    "require": {},
    "autoload": {
        "files": [
            "src/helpers/string_helper_functions.php",
            "src/helpers/url_helper_functions.php",
            "src/helpers/view_loader_functions.php"
        ]
    }
}

```

To enable the autoloading run the command:
```bash
composer dump-autoload
```

Then update your `index.php` to use composer's autoload instead of manually requiring:

```php
<?php

require_once('vendor/autoload.php');

const LOCAL_PATH = __DIR__ . '/';

$route = get_request_route();
switch($route) {
    case '/':
        view('profile.index', [
            'name' => 'Ervinne'
        ]);
        break;
    case '/contact':
        if (is_request_method('POST')) {
            echo 'will be handled';
        }
        break;
    default:
        throw new \Exception("Unhandled route {$route}");
}
```

Now try refreshing the your website locally to see that it still works despite removing our previous requires.

## PSR

PSR defines certain standards in developing in PHP.

There are quite a few but for now, we’re only really interested in PSR-4 which defines the Autoloading Standard

## PSR-4

This PSR describes a specification for autoloading classes from file paths. It is fully interoperable and can be used in addition to any other autoloading specification, including PSR-0. This PSR also describes where to place files that will be autoloaded according to the specification.

### Defining a PSR-4 autoload in our composer:

Update your `composer.json` file to add the following inside the `autoload` key:
```json
    "psr-4": {"Http\\": "src/http"},
```

Your `composer.json` should look something like:
```json
{
    "name": "ervinne/gg-byo1-bs-website",
    "description": "My Bootstrap Website",
    "authors": [
        {
            "name": "Ervinnne Sodusta",
            "email": "ervinnesodusta13@yahoo.com.ph"
        }
    ],
    "require": {},
    "autoload": {
        "psr-4": {"Http\\": "src/http"},
        "files": [
            "src/helpers/string_helper_functions.php",
            "src/helpers/url_helper_functions.php",
            "src/helpers/view_loader_functions.php"
        ]
    }
}

```

Dump our autoload again since we did an update:
```bash
composer dump-autoload
```

This will then enable us to load classes based on namespace.

### Trying out PSR-4

Create a new folder `/src/http/Controllers` and inside it the files

File `ContactsController.php`:
```php
<?php

namespace Http\Controllers;

use Http\Requests\StoreContactRequest;

class ContactsController
{
    public function store(StoreContactRequest $request)
    {
        echo "We'll call a model here later.";
    }
}
```

Create a new folder `/src/http/Requests` and inside it create a new file called `StoreContactRequest.php` with the following contents:

File `StoreContactRequest.php`
```php
<?php

namespace Http\Requests;

use Http\Requests\Request;

class StoreContactRequest extends Request
{
    protected $fillable = [
        'email', 'name', 'purpose', 'message'
    ];

    public function __construct()
    {
        $this->fill_post($this->fillable);
    }
}
```

Add another file in the `/src/http/Requests` folder called `Request.php`.

File `Request.php`
```php
<?php

namespace Http\Requests;

class Request
{
    public function fill_post($fields = [])
    {
        foreach($fields as $field) {
            $this->$field = $this->post_input($field);
        }
    }

    public function post_input($name) 
    {
        return filter_input(INPUT_POST, $name, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}
```

Finally, update `index.php` to make use of the controller and request:

File `index.php`
```php
<?php

require_once('vendor/autoload.php');

const LOCAL_PATH = __DIR__ . '/';

use Http\Controllers\ContactsController;
use Http\Requests\StoreContactRequest;

$route = get_request_route();
switch($route) {
    case '/':
        view('profile.index', [
            'name' => 'Ervinne'
        ]);
        break;
    case '/contact':
        if (is_request_method('POST')) {
            //  We'll refactor this later to use a better routing system.
            $request = new StoreContactRequest();

            $controller = new ContactsController();
            $controller->store($request);
        }
        break;
    default:
        throw new \Exception("Unhandled route {$route}");
}
```