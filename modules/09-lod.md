# The Law of Demeter

LoD is an important guideline for developers to better implement [loose coupling](https://en.wikipedia.org/wiki/Loose_coupling). Let's first learn about coupling and decoupling before we move on to really exploring LoD.

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

## The Law of Demeter (Finally Explained)

Now that we know the pre-requisites to learn LoD, let's discuss.

//  TODO: