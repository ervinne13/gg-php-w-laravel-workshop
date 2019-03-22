# Dependency Inversion Principle

To understand dependency inversion, let's first look at traditional dependency flow (non inversed).  

![fig3](/img/fig3.PNG)

A more concrete example of this is something like the following:

```php
<?php

class PayrollProcessController
{
    function handleProcessPayroll(Request $request)
    {
        //  ... does something with $request to 
        //  generate Employee $employee
        $payrollProcessor = new PayrollProcessor();
        $payrollProcessor->process($employee);
    }
}

class PayrollProcessor
{
    function process(Employee $employee)
    {
        $payrollItemsLookupSrvc = new PayrollItemsLookupService();
        $payrollItemsLookupSrvc->getFrom($employee);
        //  ...
    }
}

class PayrollItemsLookupService
{
    //  ...
}
```

In this case, the dependency flow goes "deep" to the code. The deeper the part of the code the lower the abstraction level.

This implementation generates classes that are __tightly coupled to each other__.

This type of flow is not very maintainable and forces us to update the "upper" classes whenever we need to change implementations. Moreover, we don't have the option to interchange implementations should we need to. To resolve this, we need to "invert" the dependency map where something in the higher level of abstraction manages the dependencies for us instead. We'll then have to use `Dependency Injection`.

## Dependency Injection vs Service Location

`Dependency Injection` is a very simple concept that lots of people like to over complicate. DI is merely passing in the dependencies to a dependent instead of letting it service locate. __As simple as that!__

## Inverting Dependency Flow by Dependency Injection

Refactoring our previous code, we can `invert` the dependency and let the upper abstraction levels to handle dependency lookup by:

```php
<?php

class PayrollProcessController
{
    function handleProcessPayroll(Request $request)
    {
        //  ... does something with $request to 
        //  generate Employee $employee
        $payrollItemsLookupSrvc = new PayrollItemsLookupService();
        $payrollProcessor = new PayrollProcessor($payrollItemsLookupSrvc);
        $payrollProcessor->process($employee);
    }
}

class PayrollProcessor
{
    function __construct(PayrollItemsLookupService $payrollItemsLookupSrvc)
    {
        $this->payrollItemsLookupSrvc = $payrollItemsLookupSrvc;
    }

    function process(Employee $employee)
    {
        $this->payrollItemsLookupSrvc->getFrom($employee);
        //  ...
    }
}

class PayrollItemsLookupService
{
    //  ...
}
```

## Inverting Dependency Flow by Using a Container

Using a container though, would eliminate the need for the upper levels to manually create instances of dependencies of its dependencies.

Our objective is to have a flow that looks something like below:

![fig4](/img/fig4.PNG)

```php
<?php

class PayrollProcessController
{
    function handleProcessPayroll(Request $request)
    {
        //  ... does something with $request to 
        //  generate Employee $employee
        $payrollProcessor = $this->container->get(PayrollProcessor::class);
        $payrollProcessor->process($employee);
    }
}

class PayrollProcessorDefaultImpl implements PayrollProcessor
{
    function process(Employee $employee)
    {
        $payrollItemsLookupSrvc = $this->container->get(PayrollItemsLookupService::class);
        $payrollItemsLookupSrvc->getFrom($employee);
        //  ...
    }
}

class PayrollItemsLookupServiceLocalImpl implements PayrollItemsLookupService
{
    //  ...
}
```

Where the container is the external dependency manager that may look something like:

```php
<?php

class Container
{
    function register($abstract, $concrete)
    {
        //  ... 
    }

    function get($abstract)
    {
        //  ...
    }
}
```

Think of containers as a big bag of references to objects. Many frameworks makes use of containers, in fact, this is an oversimplified version of Laravel's core.

HOWEVER! With this, we lost the ability of dependency injection. What if we want to use these classes outside the container?

Laravel and many different frameworks also enables us to do `automated dependency injection` where the container and the framework is automating the task object lookup and return our code in to injected code.

With automated dependency injection, we can do something like:

```php
<?php

class PayrollProcessController
{
    function handleProcessPayroll(Request $request, PayrollProcessor $payrollProcessor)
    {
        //  ... does something with $request to 
        //  generate Employee $employee        
        $payrollProcessor->process($employee);
    }
}

class PayrollProcessor
{
    function __construct(PayrollItemsLookupService $payrollItemsLookupSrvc)
    {
        $this->payrollItemsLookupSrvc = $payrollItemsLookupSrvc;
    }

    function process(Employee $employee)
    {
        $this->payrollItemsLookupSrvc->getFrom($employee);
        //  ...
    }
}

class PayrollItemsLookupService
{
    //  ...
}
```

In this setup, we are relying on the framework to "autowire" or "auto inject" the dependencies for us. We only need to `Type Hint` our dependencies and the dependencies are automatically looked up and instantiated for us.

Follow along the instructor as he demonstrates how this is done with Laravel.