# Introduction to MySQL Databases and PDO

## Database Design and Basic Statements

Follow along the instructor as he discuss about database design best practices and concepts:
- Composing ERD
- Forward Engineering
- CRUD:
    - Select Queries
    - Insert & Batch Insert Statements
    - Update Statements
    - Delete Statements
- Joins
- Sub Queries
- Re-considering sub queries and delegating complexity to the app.
    - We'll discuss how laravel avoids sub queries.

## Preparing Environment Variables For Connecting to a Database

We'll make use of a `.env` file to store our database credentials (so we don't need to hardcode it).

Technically, we can just define environment variables (in windows, that's literally your environment varialbes in the system properties), but we also have the option of using `.env` files which is ideal for development environment.

To enable reading `.env` files, let's make use of this library:
```bash
composer require vlucas/phpdotenv
```

We'll have to update our `.gitignore` file so we the `.env` file is not pushed to our repository (only the `.env.example`).

File `.gitignore`:
```
.env
/vendor/
```

Create two files `.env` and `.env.example`.

File `.env.example`:
```env
DB_IMPL=MySQL
DB_HOST=
DB_NAME=
DB_USERNAME=
DB_PASSWORD=
```

File `.env`:
```env
DB_IMPL=MySQL
DB_HOST=127.0.0.1
DB_NAME=gg-bs-site
DB_USERNAME=root
DB_PASSWORD=
```

To enable getting the `env` variables, we'll update our `index.php` to initialize the `dotenv` library.

We'll add the following to the `index.php`
```php
use Dotenv\Dotenv;

$dotenv = Dotenv::create(LOCAL_PATH);
$dotenv->load();
```

Now, our `index.php` should look something like:
```php
<?php

require_once('vendor/autoload.php');

use Dotenv\Dotenv;
use Http\Controllers\ContactsController;
use Http\Requests\StoreContactRequest;

const LOCAL_PATH = __DIR__ . '/';

$dotenv = Dotenv::create(LOCAL_PATH);
$dotenv->load();

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

## Using MySQL with our form

Let's first create a module that will contain the `store contact request command`.

Create new folder `/src/modules/Contact` and inside it create a new file called `StoreContactRequestCommand.php`.

File `StoreContactRequestCommand.php`: 
```php
<?php

namespace Modules\Contact;

use PDO;

class StoreContactRequestCommand
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function execute($data)
    {
        $connection = $this->get_connection();
                
        $statement = $connection->prepare("INSERT INTO contact_requests (`email`, `name`, `purpose_code`, `message`) VALUES (:email,:name,:purpose,:message)");

        //  throws PDOException
        $statement->execute($data);
    }

    private function get_connection()
    {
        $host = $this->connection['host'];
        $name = $this->connection['name'];
        $dsn = "mysql:host={$host};dbname={$name};charset=UTF8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        //  throws PDOException
        return new PDO($dsn, $this->connection['username'], $this->connection['password'], $options);
    }
}

```

We'll then update our controller to create this command and execute this command.

Update the store function to be like below:
```php
public function store(StoreContactRequest $request)
{
    $connection = [
        'host'      => getenv('DB_HOST'),
        'name'      => getenv('DB_NAME'),
        'username'  => getenv('DB_USERNAME'),
        'password'  => getenv('DB_PASSWORD'),
    ];

    $command = new StoreContactRequestCommand($connection);
    $command->execute($request->to_array());
    echo 'Thank you';
}
```

Try submitting the form with complete data and it should return "Thank you" afterwards. Note that we haven't done any validation yet so it should generate an error on submitting the same email twice or if one does not submit a full form.

## Delegating Connection Setup and Future Proofing for Use of Other Databases

We should'nt really do anything database related in the controllers. That's the `models'` job, or in this case, the `modules'`.

So we'll implement another component that can set up the command for us.

We can do a modified `strategy design pattern` implementation so that we can `future proof` our application as we'll eventually use `sqlite` for this as well when we do unit testing.

### Step 1 Update the Command to be an Implementation of an Interface

Update it's name and file name from `StoreContactRequestCommand` to `StoreContactRequestCommandMySQLImpl.php`

Next, have it use `Modules\Contact\StoreContactRequestCommand` and implement it:

```php

<?php

namespace Modules\Contact;

use Modules\Contact\StoreContactRequestCommand;
use PDO;

class StoreContactRequestCommandMySQLImpl implements StoreContactRequestCommand
{
    //  ... the content stays as is.
}
```

### Step 2 Define an Interface Other Classes Can Implement

Create a new file called `StoreContactRequestCommand` (which is an interface this time) in the same folder `/src/modules/Contact`.

File `StoreContactRequestCommand`:

```php
<?php

namespace Modules\Contact;

interface StoreContactRequestCommand
{
    function execute($data);
}
```

### Step 3 Define our "Context"

This "context" will be responsible for switching out implementations and setting up our commands:

Create a new file called `StoreContactRequestCommandContext` which contains:

```php
<?php

namespace Modules\Contact;

use Exception;
use Modules\Contact\StoreContactRequestCommand;
use Modules\Contact\StoreContactRequestCommandMySQLImpl;

class StoreContactRequestCommandContext
{
    public function create(): StoreContactRequestCommand
    {
        $dbImpl = getenv('DB_IMPL');
        switch ($dbImpl) {
            case 'MySQL':
                $connection = [
                    'host'      => getenv('DB_HOST'),
                    'name'      => getenv('DB_NAME'),
                    'username'  => getenv('DB_USERNAME'),
                    'password'  => getenv('DB_PASSWORD'),
                ];
                return new StoreContactRequestCommandMySQLImpl($connection);
            //  Other Implementations here
            default:
                throw new Exception("Unrecognized implementation {$dbImpl}");
        }
    }
}
```

With this, we can now remove the connection information in our controller and make use of the context instead.:

```php
<?php

namespace Http\Controllers;

use Http\Requests\StoreContactRequest;
use Modules\Contact\StoreContactRequestCommand;
use Modules\Contact\StoreContactRequestCommandContext;

class ContactsController
{
    public function store(StoreContactRequest $request)
    {   
        $commandContext = new StoreContactRequestCommandContext();
        $command = $commandContext->create();
        $command->execute($request->to_array());
        echo 'Thank you';
    }
}
```

Try it out again and it should result in the same output as expected.

Tip: you can reuse emails by adding `+<whatever number>` in the email before `@`.

Ex.

ervinne.sodusta+1@gmail.com.