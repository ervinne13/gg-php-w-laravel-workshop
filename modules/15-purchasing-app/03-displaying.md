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