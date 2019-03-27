# Displaying Purchase Orders

Since the display is not API based, we can't do a unit or feature test for this so we'll go straight to browser tests. We want to display a table of purchase orders with links edit, view, and delete in them.

## Step 1 Update the Tests

Lets update our original test from `it_can_accept_valid_purchase_orders` to `it_can_accept_valid_purchase_orders_and_display_results` and instead of asserting that the database has the purchase orders, we assert that there are purchase orders displayed after submit.

```php
 /** @test */
public function it_can_accept_valid_purchase_orders_and_display_results()
{
    $this->browse(function (Browser $browser) {
        $browser
            ->visit('/po/create')
            ->assertSee('Buyer')
            ->assertSee('Supplier')
            ->assertSee('Total Cost')
            ->assertSee('Breakdown')
            ->assertSee('Purpose');

        $po = [
            'buyer'         => 'Ervinne Sodusta',
            'supplier'      => 'Jet Lighting LTD',
            'total_cost'    => 45000,
            'breakdown'     => 'Tube light Industrial 4w x 20, Tube light Industrial 7w x 15',
            'purpose'       => 'Replenishment'
        ];

        foreach($po as $field => $value) {
            $browser->type($field, $value);
        }

        $browser
            ->press('Submit')
            ->on(new PurchaseOrderIndexPage())
            ->with('.table', function($table) use ($po) {
                $table
                    ->assertSee($po['buyer'])
                    ->assertSee('View')
                    ->assertSee('Edit')
                    ->assertSee('Delete');
            });
    });
}   
```

For this to work, we'll need to define a new page that dusk can redirect to, which is `PurchaseOrderIndexPage`. Create this page by using the command:

```bash
php artisan dusk:page PurchaseOrderIndexPage
```

Then edit the `url` method to return the correct url:

```php
public function url()
{
    return '/po';
}
```

## Step 2 Create View for Index Page

Create a new file `/resources/views/po/index.blade.php` with the contents:
```html
@extends('layout.default')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>Buyer</th>
            <th>Supplier</th>
            <th>Purpose</th>
            <th>Actions</th>
        <tr>
    </thead>
    <tbody>
        @foreach($poList as $po)
        <tr>
            <td>{{$po->buyer}}</td>
            <td>{{$po->supplier}}</td>
            <td>{{$po->purpose}}</td>
            <td>View Edit Delete</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
```

Note that we'll be updating the actions later.

Update the controller's `index` method to display the purchase orders:

```php
public function index()
{
    $poList = PurchaseOrder::all();

    return view('po.index', ['poList' => $poList]);
}
```

# Displying Specific Purchase Orders

This time, we'll display each purchase orders that we previously saved. For this, we'll also learn about seeding test data using factories.

When testing, it's not ideal to duplicate or nest tests so repeating the previous test's procedure of inserting data then testing this new view should not be preferred. We'll be seeding data instead, effectively creating a "black box" and a predefined ideal scenario before the actual test.

## Step 1 Creating a Factory for Seeding Data

Prepare for testing later by creating our factory with:

```bash
php artisan make:factory PurchaseOrderFactory
```

This will create a new file `/database/factories/PurchaseOrderFactory.php`, replace its contents with the following:

```php
<?php

use App\Models\PurchaseOrder;
use Faker\Generator as Faker;

$factory->define(PurchaseOrder::class, function (Faker $faker) {
    return [
        'buyer'         => $faker->name(),
        'supplier'      => $faker->name(),
        'total_cost'    => $faker->randomFloat(2, 5000, 25000), // 2 decimal points, ranged from 5,000 to 25,000
        'breakdown'     => $faker->sentence,
        'purpose'       => $faker->sentence
    ];
});

```

You can test this by using the factory helper method. Let's try it with tinker, fire up your tinker:

```bash
php artisan tinker
```

... and inside your tinker, do:

```php
factory(App\Models\PurchaseOrder::class, 10)->create();
```

Check your database and it should now contain 10 (or more if you created more manually).

## Step 2 Create our Test for Viewing Specific Purchase Orders

```php
/** @test */
public function it_can_view_created_purchase_orders()
{
    $poList = factory(PurchaseOrder::class, 10)->create();
    
    $this->browse(function (Browser $browser) use ($poList) {
        foreach($poList as $po) {
            $totalCostDisplay = 'P' . number_format($po->total_cost);

            $browser
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

## Step 3 Creating our Display View

We'll be creating a simple non editable view of the purchase order. In the real world, this is usually the print ready version.

Create new file `/resources/views/po/view.blade.php` with the contents:
```html
@extends('layout.default')

@section('content')

<p>
    <b>Buyer:</b> <label>{{$po->buyer}}</label>
</p>
<p>
    <b>Supplier:</b> <label>{{$po->supplier}}</label>
</p>
<p>
    <b>Total Cost:</b> <label>P{{number_format($po->total_cost)}}</label>
</p>

<hr />

<b>Breakdown:</b>
<br />
{{$po->breakdown}}

<hr />

<b>Purpose:</b>
<br />
{{$po->purpose}}

@endsection 
```

Then in the controller `PurchaseOrderController`, insert the following code inside the `show` method:
```php
$po = PurchaseOrder::findOrFail($id);
return view('po.view', ['po' => $po]);
```

Now that we're able to display the purchase order, run your tests again to validate that the view is working.

