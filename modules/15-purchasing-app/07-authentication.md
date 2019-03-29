# Implementing Authentication

## Laravel Authentication Scaffolding

Laravel ships a secure login scaffolding already that you pretty much don't need to do much. In fact, it's login system would already pass common security scanners. If you don't need to do anything very custom with login, stick to what Laravel provides out of the box.

To setup Laravel authentication scaffolding, simply run the artisan command:

```bash
php artisan make:auth
```

Your app now as full authentication features just with this. 

## Protecting Routes

We can prevent access to our `/po` routes and allow only those authenticated by adding an auth middleware in the routes.

Wrap the `/po` route with `auth` middleware with:

```php
Route::group(['middleware' => 'auth'], function() {
    Route::resource('/po', 'PurchaseOrderController');
});
```

Running your tests now would fail since merely trying to access `/po` route will cause an error and redirect the user to the `/login` route.

Checkout the login page though. Since it's provided by laravel, it's not yet using AdminLTE.

We go back to the docs of AdminLTE and we'll see that there's an easy fix for this. Run:

```bash
php artisan make:adminlte
```

... and just reply yes to each questions as we already have existing views.

## Enabling Authenticated Use in Browser Tests

Laravel has factory ready for the `User` class so we can just reuse it and use the `loginAs` method of Laravel Dusk. (In non browser test, you use `actingAs` instead)

Update the `PurchaseOrderBrowserTest` and add a factory creation of `User` and `$browser->loginAs` to each test. Ex.

```php
$userList = factory(User::class, 1)->create();
$user = $userList[0];

//...

$browser->loginAs($user)

```

Example, the `it_can_view_created_purchase_orders` test will be modified to be:

```php
/** @test */
public function it_can_view_created_purchase_orders()
{
    $poList = factory(PurchaseOrder::class, 5)->create();
    $userList = factory(User::class, 1)->create();
    $user = $userList[0];

    $this->browse(function (Browser $browser) use ($poList, $user) {
        foreach($poList as $po) {
            $totalCostDisplay = 'P' . number_format($po->total_cost);

            $browser
                ->loginAs($user)
                ->visit("/po/{$po->id}")
                ->assertSee($po->buyer)
                ->assertSee($po->supplier)
                ->assertSee($totalCostDisplay)
                ->assertSee($po->breakdown)
                ->assertSee($po->purpose);
        }
    });
}
```

Apply the same thing to all tests and it should work go back to green again.

## Restoring Feature Tests

Our feature tests area also affected, we should be able to make them green again by using `actingAs` function similar to what we did in our browser tests.
In the file `/tests/Feature/PurchaseOrderFeatureTest` update the function `it_can_accept_valid_purchase_orders` to:

```php
/** @test */
public function it_can_accept_valid_purchase_orders()
{
    $po = [
        'buyer'         => 'Ervinne Sodusta',
        'supplier'      => 'Jet Lighting LTD',
        'total_cost'    => 45000,
        'breakdown'     => 'Tube light Industrial 4w x 20, Tube light Industrial 7w x 15',
        'purpose'       => 'Replenishment'
    ];

    $userList = factory(User::class, 1)->create();
    $user = $userList[0];

    $response = $this->actingAs($user)->json('POST', '/po', $po);
    $response->assertRedirect('/po');
    $this->assertDatabaseHas('purchase_orders', $po);
}
```

You also have to make sure that in all tests, you add in a `use` statement to import the `User` model class.

```php
use App\User;
```