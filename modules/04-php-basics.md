# PHP

## Variables, Numbers, Dates & Strings

Follow along the instructor as he discuss about `typed variables` vs `typed values` and other things.

Main Points:
- PHP has `typed values`;
- PHP supports 10 primitive types
    - Scalar Types
        - boolean
        - integer
        - float (floating-point number, aka double)
        - string
    - Compound Types
        - array
        - object (defined by classes)
        - callable
        - iterable (pseudo-type introduced in PHP 7.1)
    - Special Types
        - resource
        - NULL

Notes on special and pseudo types:

__Iterable__ is an array or object implementing the `Traversable` interface.

__Resource__ is a special variable, holding a reference to an external resource. Resources are created and used by special functions.

```php
// prints: mysql link
$c = mysql_connect();
echo get_resource_type($c) . "\n";

// prints: stream
$fp = fopen("foo", "w");
echo get_resource_type($fp) . "\n";

// prints: domxml document
$doc = new_xmldoc("1.0");
echo get_resource_type($doc->doc) . "\n";
?>
```

__NULL__ is value that represents a variable with no value.

A variable is considered null if:
- it has been assigned the constant NULL.
- it has not been set to any value yet.
- it has been unset().

## String Concatenation and Templates

You can do string concatenation by using the `.` operator.

Example

```php
<?php
echo "Hello" . " " . "World";
```

Prints out `Hello World`

You may also "insert" variables in string by enclosing them in curly braces. Note that you'll have to use double quotes for defining strings if you intend to do this.

Example:
```php
<?php
$name = "Ervinne"
echo "Hello {$name}";
```

## PHP As CLI Commands

PHP can be run in your terminal, this is useful for programs that you want to run in the server only without exposing it in the web.

One example of this use case is creating a default `super admin` for your website, exposing this to the web is a security issue so we can do this in CLI instead.

To run a PHP script as a command in CLI you can do:

```bash
php my-cli.php
```

To view more things you can do with the php command, use the command: `php --help`.

### Try it out

Create a file called `my-cli.php` with the following contents:

```php
<?php

echo "Hello World";

// prints new line, you can also use \n 
//but its better to use built in constants for this
echo PHP_EOL;
```

Then run it using:

```bash
php my-cli.php
```

### Accepting CLI Arguments

CLI Arguments are exposed via `$argv` global variable. Note that this is an array that pertains to all tokens (think of this as words) passed after the `php` command.

Modify your `my-cli.php` to:
```php
<?php

echo "Hello World";
print_r($argv);

echo PHP_EOL;
```

... and run the command:

```bash
php my-cli.php my-value
```

It should output something like:
```
Hello WorldArray
(
    [0] => my-cli.php
    [1] => my-value
)
```

### Activity

Let's create a command that prints out your name.

Create a new file called `greet-name` (this is not a typo, create a file without extension) with the following content:

```php
<?php

if (count($argv) < 2) {
    die("This command requires a name as it's parameter");
}

echo "Hello {$argv[1]}";
echo PHP_EOL;
```

Tip: the `die` function is used when you want to stop execution and return an error message.

WARNING! We're only using die here as example, avoid this in your code as much as possible and we'll use a better alternative: `Exceptions` which we will discuss later on after `Object Oriented Programming`.

Execute your command using:

```php
php greet-name Ervinne
```

The command should now greet with your name.
This is also foreshadowing about Laravel's `artisan` command. This is exactly how Laravel does it (except of course, this is simpler as we're merely printint our name).

## Type Coercion

PHP is a loosely typed language that allows you to declare a variable and its type simply by using it. It also automatically converts values from one type to another whenever required. This is called `implicit casting`.

### Implicit & Explicit Casting

- Implicit: Letting PHP do the casting as you use the variables
- Explicit: Using PHP functions to type cast. (`intval`, `floatval`, etc.)

You may also cast using the following:

```php
echo (int) "1.5"; // prints 1
```

Cast Types Reference

| Cast Type | Description |
|-----------|-------------|
|(int) (integer)|Cast to an integer by dropping the decimal portion|
|(bool) (boolean)|Cast to a Boolean|
|(float) (double) (real)|Cast to a floating-point number|
|(string)|Cast to a string|
|(object)|Cast to an object|

## Control Structures

Control structures `drive` a program, these are things like conditional statements and loops.

### Conditional Statements

Conditional Statements allow you to branch the path of execution in a script based on whether a single, or multiple conditions, evaluate to true or false. Put simply, they let you test things and perform various actions based on the results.

__If statements__

```php
<?php
$x = 1;

if ($x === 1) {
    print '$x is equal to 1'; 
}
```

__Conditional Operators__

| Operator | Description |
|----------|-------------|
| == | Tests for equality after doing an `implicit coercion` |
| === | Test for equality without doing `implicit coercion` which means it tests for the data type as well. |
| < | Tests if the variable in the left is less than the right |
| > | Tests if the variable in the left is greater than the right |
| <= | Tests if the variable in the left is less than or equal to the right |
| >= | Tests if the variable in the left is greater than or equal to the right |

Note that only the `===` operator removes `implicit coercion`. Meaning all aother operations `implicitly coerce` the types before comparing. Ideally, you use `===` more than `==`.

(Follow along the instructor as he demonstrates coercion in conditional operators and demonstrate `truthy` and `falsy` values).

__Multiple Conditions:__

You can use `OR` (equivalent to `||`) or `AND` (equivalent to `&&`) to combine multiple 

```php
<?php
$x = 1;

if ($x === 1 OR $x === 2) {
    print '$x is equal to 1 (or maybe 2)'; 
}
```

__Else Statements:__

As the name implies, Else Statements allow you to do something else if the condition within an If Statement evaluated to false:

```bash
<?php
$x = 1;

if ($x === 2) {
    print '$x is equal to 2';
} else {
    print '$x is equal to 1';
} 
?>
```

__Else If Statements__

Thus far, we have been able to respond to one condition, and do something if that condition is not true. But what about evaluating multiple conditions? You could use a series of If Statements to test each potential condition, but in some situations that is not a suitable option. Here is where Else If Statements come in.

A combination of If and Else Statements, Else If Statements are evaluated sequentially if the condition within the If Statement is false. When a condition within an Else If Statement evaluates to true, the nested statements are parsed, the script stops executing the entire If/Else if/Else Structure. The rest of the script proceeds to be parsed.

Take a look at the following example: 

```php
<?php
$x = 1;

if ($x === 2) {
    print '$x is equal to 2';
} else if ($x === 1) {
    print '$x is equal to 1';
} else {
    print '$x is not equal 2 or 1';
}
?>
```

### Ternary Operator

You can do a `terse` version of the if else statement using the ternary operator with syntax `<condition> ? <statement if true> : <statement if false>;`

Example:

```php
<?php

$isEnabled = true;

print ($isEnabled) ? 'The thing is enabled' : 'The thing is NOT enabled';
```

### Switches

Switches are a good alternative to If/Else if/Else Statements in situations where you want to check multiple values against a single variable or condition. 

This is the basic syntax:

```php
<?php
$animal = "dog";

switch ($animal) {
    case "dog":
        print 'bark!';
        break;
    case "cat":
        print 'meow~';
        break;
    case "ex":
        print "It's not you, it's me :(";
        break;
    default:
        print "I don't know what kind of animal you are";
}
```

### Quick Activity

On your own for (10 - 15 minutes):

1. Create a CLI command that accepts 2 parameters.
    - Running this command should look like `php operate 2 3` where 2 and 3 are your parameters
2. The CLI command should output:
    ```
    Operating on 6 and 3
    It's sum is 9
    It's difference is 3
    It's product is 18
    It's quotient is 2

    It's sum is less than 10
    ```
3. Use conditional operators to print if the sum is less or greater than 10
    - If it's more than 10 print out: `It's sum is less greater than 10`
    - Otherwise print: `It's sum is less than 10`

Tip: you can do math operations on `literals` and `variables with:

```php
$x = 6;
$y = 3;

$sum = 6 + 3;
$difference = 6 - 3;
$product = 6 * 3;
$quotient = 6 / 3;

echo "It's sum is {$sum}";
echo "It's difference is {$difference}";
echo "It's product is {$product}";
echo "It's quotient is {$quotient}";
```

### Control Loops

There are many cases where you need to do repititive tasks in many applications. For this cases, you can use loops.

#### While Loops

While loop is a loop that `FIRST` tests for a condition, then does the code inside its scope (inside curly braces) until that condition is false.

Basic Syntax:

```php
<?php
$x = 1;

while ($x <= 10) {
   print "$x" . PHP_EOL;
   $x ++; 
}
```

This script will print 1 to 10 in 10 lines.

#### Do ... While Loops

Do ... while loop is a loop that does the action first THEN tests for a condition. If the condition results to false, it stops the execution and proceeds to the next one, otherwise (if true), it repeats the operation

Example:

```php
<?php
$x = 11;

do {
    print "$x<br>";
    $x ++; 
} while ($x <=10);
```

This script should print out 11 and nothing else since 11 > 10.

#### For Loops

A for loop is a convinient while loop where the declaration, condition, and incrementing operation is done in one line so you can concentrate on the operation inside the braces.

Example:

```php
<?php
for($x = 1; $x <= 10; $x ++) {
    print "$x" . PHP_EOL;
}
```

Which is pretty much the same as our example in `while loops`.

#### Pre and Post Increment/Decrement Operation

As you've noticed in loops, you can increment a variable with `$x++`. You can decrement as well with `$x--`.

`$x++` is the same as `$x = $x + 1` which tells us to assign the value of `$x + 1` to `$x` effectively incrementing it.

There's a `pre` increment operation as well done using `++$x`. This means you increment first before evaluating the current line.

Consider the code below:

```php
<?php

$x = 10;
if ($x++ > 10) {
    print('x is greater than 10');
} else {
    print('x is less than or equal to 10');
}
```

This prints out 'x is less than or equal to 10' because the condition is being evaluated BEFORE incrementing `$x`.

On the otherhand, if we did:

```php
<?php

$x = 10;
if (++$x > 10) {
    print('x is greater than 10');
} else {
    print('x is less than or equal to 10');
}
```

This will instead print 'x is greater than 10'.
Same applies for decrements.

#### For Each Loops

A foreach loop is good for iterating through arrays or iteratables.

Example:

```php
<?php

$cleaners = [
    'Ervinne', 'John', 'Maria'
];

$cleanersHTML = '';
foreach($cleaners as $cleaner) {
    $cleanersHTML .= "<li>{$cleaner}</li>";
}

echo "<ul>{$cleanersHTML}</ul>";
```

This should print out:
```html
<ul><li>Ervinne</li><li>John</li><li>Maria</li></ul>
```

Tip: you'll often use this generating lists or options for your select/dropdowns in your UI.

## PHP & HTML

One of PHP's greatest assets is that it works really well with HTML out of the box.

In fact, you can embed PHP variables and operations to HTML directly as long as you indicate `.php` or `.phtml` as the file's extension.

Example: Embedding Variables to HTML:
File: `my-dropdown.php`

```html
<html>
    <head>
        <title>My Site</title>
    </head>
    <body>
        <!-- Embedding PHP: -->
        <?php $options = ['Dog', 'Cat', 'Ex'] ?>
        <select name="animal">
            <?php foreach($options as $option): ?>
            <!-- Embedding PHP and printing it right away -->
            <option><?= $option ?></option>
            <?php endforeach ?>
        </select>
    </body>
</html>
```

In summary:

`<?php ... ?>` embeds a php script.

`<?= ?>` is a shortcut to embed and printout a PHP variable.

### Best Practice

A lot of new developers get this wrong but you MUST prefer writing html views that embed PHP instead of PHP that embeds or prints out HTML!

__BAD__ Example:

```php
<?php
$optionsHTML = '';
foreach($options as $option) {
    $optionsHTML .= '<option><?= $option ?></option>';
}

$myHTML = '<html>
    <head>
        <title>My Site</title>
    </head>
    <body>
        <select name="animal">' . $optionsHTML . '</select>
    </body>
</html>';

echo $myHTML;
```