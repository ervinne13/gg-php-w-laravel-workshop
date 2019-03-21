# The Law of Demeter

LoD is an important guideline for developers to better implement [loose coupling](https://en.wikipedia.org/wiki/Loose_coupling). 

Law of demeter is also called the __principle of least knowledge__. It dictates that a dependency class should know very little about its dependency. By very little, we mean __just enough__ for the dependent object to do its job.

The law(s) are as follows:

- Each unit should have only limited knowledge about other units: only units "closely" related to the current unit.
- Each unit should only talk to its friends; don't talk to strangers.
- Only talk to your immediate friends.

To better understand this, we'll discuss coupling and dependency injection to better implement the law of demeter, then go back to LoD to try make a better sense of it.

## Coupling

Coupling is a concept of how great does a certain unit of code or class is dependent on another. This is one of the most fundamental concepts one must learn when treading the path to learning SOLID and Design Patterns.

### Example on Tight Coupling

Bear in mind that tightly coupled objects is general bad practice and will prevent you from implementing single responsibility principle and open/closed principle (more on this later on SOLD Principles).

Consider a code `CoffeeMaker` that (as the name suggest) makes coffee.

```php
<?php


class CoffeeBeans
{
    //  ... some properties
}

class CoffeeMaker
{
    private $drinkableCoffee;

    public function make()
    {
        $filter = new PaperFilter();
        $beans = new CoffeeBeans(); // let's assume that this is from Starbucks

        $groundBeans = $this->grind($beans);        
        $this->drinkableCoffee = $this->filter($groundBeans, $filter);
    }

    public function pour($percent = 5)
    {
        return $this->$drinkableCoffee->takePercent($percent);
    }

    public function grind() : GroundBeans
    {
        //  ...
    }

    public function filter(GroundBeans $groundBeans, PaperFilter $filter) : FilteredCoffee
    {
        //  ...
    }
}

```

In this code, `CoffeeMaker` is tightly coupled to `CoffeeBeans` and `PaperFilter`. There's really only one kind of filter a coffee maker accepts (at least from what I know) so we can let `PaperFilter` slide (we'll still "fix" this anyway). __The main problem is with `CoffeeBeans`__. What if we want to use a different kind of coffee bean than Starbucks'?

We'll then polymorph `CoffeeBeans` and turn it into an interface where we can interchange it with different `implementations`.

### Refactored Example to Implement __Loose Coupling__

```php
<?php


interface CoffeeBeans
{
    //  ... some methods (if there are any)
}

class StarbucksCoffeeBeans implements CoffeeBeans
{
    //  ...
}

class KrispyKremeCoffeeBeans implements CoffeeBeans
{
    //  ...
}

class CoffeeMaker
{
    private $drinkableCoffee;

    private $beans;
    private $filter;

    public function prepare(PaperFilter $filter, CoffeeBeans $beans)
    {
        $this->filter = $filter;
        $this->beans = $beans;
    }

    public function make()
    {
        $groundBeans = $this->grind($this->beans);        
        $this->drinkableCoffee = $this->filter($groundBeans);
    }

    public function pour($percent = 5)
    {
        return $this->$drinkableCoffee->takePercent($percent);
    }

    public function grind() : GroundBeans
    {
        //  ...
    }

    public function filter(GroundBeans $groundBeans) : FilteredCoffee
    {
        //  we can just use $this->filter instead of passing to params.
        //  ...
    }
}

```

What we just did is __decouple__ `CoffeeMaker` from `PaperFilter` and `CoffeeBeans`.
We don't need to change much about `PaperFilter` as we need it as is, but for `CoffeeBeans`, we implemented polymorphism to enable interchangeability between different implementations.

Interfaces are __contracts__ in software engineering. It gives us the __assurance__ that a certain object is does what it says it does. We may also use abstract classes or just normal parent classes if we want to define objects over what they are instead of what they do.

### Dependency Injection vs Service Location

The original code (tightly coupled) implements the design pattern (now anti pattern) `Service Locator Design Pattern` which lets the dependent class search or locate its own dependencies.

In our refactored version, we implemented here `Dependency Injection`.

`Dependency Injection` is a very simple concept that lots of people like to over complicate. DI is merely passing in the dependencies to a dependent instead of letting it service locate. __As simple as that!__

## The Law of Demeter (Revisited)

Now that we know the pre-requisites to learn LoD, let's discuss more about it.

Law of Demeter simply dictates you to write code where the user of another class/service that you made knows only the __least minimum__ about that class/service.

Consider the following example that does not follow the Law of Demeter:

```php

class ProductManagementService 
{
    public function getProductById($id) {/* ... */}

    public function synchronizeProducts() {/* ... */}

    public function loadProductsFromExtServer() {/* ... */}

    //  about 8 more functions here
}

class Cart
{
    public function __construct(ProductManagementService $productManagementSrvc) 
    {
        $this->productManagementSrvc = $productManagementSrvc;
    }

    public function addProductById($id, $qty)
    {
        $product = $this->productManagementSrvc->getProductById($id);
        //  ... some more code here
    }
}

```

Analyze this code and try to guess what's the problem with it. An obvious code smell is that 1: `ProductManagementService` is responsible for a lot of things that it may need to be dissected, but that's not what we're concerned with (for now let's leave it as is). But if we did try to dissect it and extract its functionalities, users of this class would have to change, lots of them. The reason is that __we did not follow the principle of least knowledge__ and let the `Cart` class depend on something large like `ProductManagementService` in the first place.

We can resolve this without changing `ProductManagementService` yet by introducing a very specific interface and use __Interface Segregation Principle__ from __SOLID__.

```php

interface ProductProvider
{
    function getProductById($id);
}

class ProductManagementService implements ProductProvider
{
    public function getProductById($id) {/* ... */}

    public function synchronizeProducts() {/* ... */}

    public function loadProductsFromExtServer() {/* ... */}

    //  about 8 more functions here
}

class Cart
{
    public function __construct(ProductProvider $productManagementSrvc) 
    {
        $this->productProvider = $productProvider;
    }

    public function addProductById($id, $qty)
    {
        $product = $this->productProvider->getProductById($id);
        //  ... some more code here
    }
}
```

This refatored code restricts `Cart`'s knowledge to the bare minimum by only letting it know that it's receiving a `ProductProvider`.

The major benefit of this is that we can now safely refactor `ProductManagementService` as well by dissecting it in a way __that does not affect the `Cart` class because we are now following the Law of Demeter__. It won't affect the `Cart` class because if we did extract the product loading functionality into a different class, that separate class would just have to implement `ProductProvider` as well and we'll just have to map the interface and implementation in our service providers (more on this later when we look at Laravel's custom container).

This also offers the developers a better view of the client code. If we injected `ProductManagementService` directly, it generates the question "what did we use this dependency for?" and we'll only really know until we've scanned everything that's using the reference. On the otherhand, `ProductProvider` is a more obvious dependency that at _first glance_ we know that we need this dependency becase we need to look up products somewhere in the code.