
# (SRP) Single Responsibility Principle

__"A class should have one, and only one, reason to change." - Robert C. Martin__

The argument for the single responsibility principle is relatively simple: it makes your software easier to implement and prevents unexpected side-effects of future changes.

### SRP Refactoring Samples

Original Code
```php
<?php

// ...

class 
```

### When SRP Goes Overkill

Note that this sample is pretty much based on personal experience of the instructor. This tries to demonstrate that sometimes, it's okay to deliberately violate SRP.

__Consider the SRPd code below:__
```php
<?php

//  ...

class Order
{
    //  ... lots of properties
}

class OrderValidator
{    
    function validate(Order $order) {/*... 20 lines of code */}
}

class OrderShipper
{
    function ship(Order $order) {/*... 12 lines of code */}
}

class OrderStockChecker
{
    function check(Order $order) {/* ... 21 lines of code */}
}

class AccountsReceivable
{
    //  lots of other functions
    function post(AccountingDocument $document) {/*...*/}
}

class OrderProcessor
{
    function __construct(
        OrderValidator $validator, 
        OrderShipper $shipper, 
        OrderStockChecker $checker,
        AccountsReceivable $ar) {/*...*/}

    function process(Order $order) {/*... 7 lines of code */}
}
```

The argument here is that `OrderValidator` is used also somewhere else, separating it from `OrderProcessor` "makes sense" because it will make validator reusable. The rest of the other classes is "futured proofed" for the same scenario as `OrderValidator` and it's implementation.

This is quite arguable, we're generating too many files now that are "not yet" necessary anyway.

The `OrderProcessor` aggregate service still encapsulates validation, stock checking and shipping of orders anyway, it's just broad. BUT if the lines of code of all combined does not exceed 120 anyway, why extract them? Why don't we WAIT for the actual need to extract if our only reason was so that the other services can be "reusable"?

The only dependency that really makes sense to separate here is `AccountsReceivable` as this is something that's accounting related, not exactly order related

__First alternative implementation:__
```php
<?php

//  ...

class Order
{
    //  ... lots of properties
}

class OrderValidator
{    
    function validate(Order $order) {/*... 20 lines of code */}
}

class AccountsReceivable
{
    //  lots of other functions
    function post(AccountingDocument $document) {/*...*/}
}

class OrderProcessor
{
    function __construct(
        // still separated because of "resuability" argument
        OrderValidator $validator,
        AccountsReceivable $ar) {/*...*/}

    function process(Order $order) {/*... 6 lines of code */}

    function ship(Order $order) {/*... 12 lines of code */}

    //  renamed from check to checkStocks since context is now lost
    function checkStocks(Order $order) {/* ... 21 lines of code */}
}
```

Did we violate SRP? Some say we did, others, it's arguable.
In this cases, I'd like to follow the rule "defend the purity".

"Defending purity" is a thought process where you deliberate and defend "are we really 'gonna need this level of SRP?". If you can answer yes with confidence, then please proceed with the current implementation where we have lots of classes. Otherwise, you might want to hold back and wait for the actual need for extraction. Again, let's follow KISS + YAGNI first before SOLID.

We can take this even further by making use of another SOLID principle called `Interface Segregation Principle`. Since our objective is just to make validation reusable by other components since it's used by something else (which in my opinion, is a code smell in itself, but let's let it slide for this sample), we can still __`retain the interface (or create one if there's none) BUT put the implementation in the aggregate`__.

__Second Alternative Implementation:__
```php
<?php

//  ...

class Order
{
    //  ... lots of properties
}

class AccountsReceivable
{
    //  lots of other functions
    function post(AccountingDocument $document) {/*...*/}
}

class OrderProcessor implements OrderValidator
{
    function __construct(AccountsReceivable $ar) {/*...*/}

    function process(Order $order) {/*... 6 lines of code */}

    //  implementing OrderValidator
    function validate(Order $order) {/*... 20 lines of code */}

    function ship(Order $order) {/*... 12 lines of code */}

    //  renamed from check to checkStocks since context is now lost
    function checkStocks(Order $order) {/* ... 21 lines of code */}
}
```

The dependency `AccountsReceivable` stays separate as this is really a separate concept if you think about it. `OrderValidation` however, is still part of the order processing so why separate if we can still achieve the original objective this way anyway?

### Loss of Object Orientedness due to SRP

The instructor would also like to argue that too much separation would sometimes also separate the data from the behavior. Which is exactly what happened here, although our final output is not much different. We will fix this soon in the next section about `anemic models`.

What we're doing is not Object Oriented Programming anymore, what we're doing is Procedural Programming by means of [Transaction Scripts](https://www.martinfowler.com/eaaCatalog/transactionScript.html)!

What happens here is that we are delegating behavior too much that there's not much left in the `Order` class making it `anemic`.

### Anemic Models

See [Martin Fowler's Documentation about it here](https://www.martinfowler.com/bliki/AnemicDomainModel.html).

Basically, an anemic model is a model that lacks behavior, thus acting more like a struct or a mere bag of properties.

A possible solution is simple, move validate and ship, and if possible, also `checkStocks` from `OrderProcessor` to the `Order` class. I say `checkStocks` is moved only if possible because it might have a dependency which `Order` will then have to resolve, meaning it was probably better if we left it as is earlier had we known this issue. For now though, let's consider checkStocks without dependencies as we've written here.

__Second Alternative Implementation:__
```php
<?php

//  ...

class Order implements OrderValidator
{
    //  ... lots of properties    

    function validate(Order $order) {/*... 20 lines of code */}

    function ship(Order $order) {/*... 12 lines of code */}

    function checkStocks(Order $order) {/* ... 21 lines of code */}
}

class AccountsReceivable
{
    //  lots of other functions
    function post(AccountingDocument $document) {/*...*/}
}

class OrderProcessor
{
    function __construct(AccountsReceivable $ar) {/*...*/}

    function process(Order $order) {/*... 6 lines of code */}
}
```

Where the `validate`, `ship`, and `checkStocks` are called inside the `Order` instead of the `OrderProcessor`.