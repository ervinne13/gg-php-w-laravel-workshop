# Interface Segratation Principle

"The interface-segregation principle (ISP) states that no client should be forced to depend on methods it does not use." - Robert Martin

Consider the following example:

```php
<?php

interface ProductManagementService
{
    function getProductById($id);
    function synchronizeProducts();
    function loadProductsFromExtServer();
    //  about 8 more functions here
}

class ProductManagementServiceDefaultImpl implements ProductManagementService
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

Analyze this code and try to guess what's the problem with it. 
An obvious code smell is that `ProductManagementService` interface is responsible for a lot of things that it may need to be dissected. In fact, `Cart` is depending on it but it's not exactly making use of the other 10 methods that the service uses.

In order to resolve this, we can first dissect the interface, and then it's up to us if we dissect and extract functions from the implementation as well.

```php
<?php

interface ProductProvider
{
    function getProductById($id);
}

interface ProductSynchronizer
{
    function synchronizeProducts();
    function loadProductsFromExtServer();
}

interface OtherProductManagementServiceFunctions
{
    //  the other 8 more functions here
}

class ProductManagementService implements ProductProvider, ProductSynchronizer, OtherProductManagementServiceFunctions
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

The major benefit of this is that we can now safely refactor `ProductManagementService` as well by dissecting it in a way __that does not affect the `Cart` class because we are now following the Interface Segregation and another concept called Law of Demeter__. It won't affect the `Cart` class because if we did extract the product loading functionality into a different class, we can just inject that class instead or map it in a container with automated dependency injection (ie: Laravel's Customer Container).

This also offers the developers a better view of the client code. If we injected `ProductManagementService` directly, it generates the question "what did we use this dependency for?" and we'll only really know until we've scanned everything that's using the reference. On the otherhand, `ProductProvider` is a more obvious dependency that at _first glance_ we know that we need this dependency becase we need to look up products somewhere in the code.

## The Law of Demeter

LoD is an important guideline for developers to better implement [loose coupling](https://en.wikipedia.org/wiki/Loose_coupling). 

Law of demeter is also called the __principle of least knowledge__. It dictates that a dependency class should know very little about its dependency. By very little, we mean __just enough__ for the dependent object to do its job.

The law(s) are as follows:

- Each unit should have only limited knowledge about other units: only units "closely" related to the current unit.
- Each unit should only talk to its friends; don't talk to strangers.
- Only talk to your immediate friends.

It's also what we implemented just now. We restricted the client class `Cart`'s knowledge from the whole product management to product lookup.