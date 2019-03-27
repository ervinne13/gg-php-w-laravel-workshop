# Editing Purchase Orders

## Step 1 Reusing our Form

We want to avoid duplicates so we'll have to reuse the existing view from `resources/views/po/form.blade.php`.
To do this, we'll have to parameterize the __action__ and the __method__ and set the value of each field.:

```html
@extends('layout.default')

@section('content')
<form action="{{$action}}" method="POST">

    @if ($method === 'PUT')
    {{ method_field('PUT') }}
    @endif

    {{csrf_field()}}
    <div class="form-group">
        <label>Buyer</label>
        <input name="buyer" value="{{$po->buyer}}" type="text" class="form-control" placeholder="The name of the creator of this PO">        
    </div>
    <div class="form-group">
        <label>Supplier</label>
        <input name="supplier" value="{{$po->supplier}}" type="text" class="form-control" placeholder="The supplier we will buy from">        
    </div>
    <div class="form-group">
        <label>Total Cost</label>
        <input name="total_cost" value="{{$po->total_cost}}" type="number" class="form-control">        
    </div>
    <div class="form-group">
        <label>Breakdown</label>
        <textarea name="breakdown" type="text" class="form-control">{{$po->breakdown}}</textarea>
    </div>
    <div class="form-group">
        <label>Purpose</label>
        <textarea name="purpose" type="text" class="form-control">{{$po->breakdown}}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection
```

Notice that this would generate regressions. Run your browser tests again to see that there are errors. Now we'll reap the benefits of TDD early.
To make this green again, we'll have to update the contents of the `create` function of `PurchaseOrderController` to pass in the necessary data:

```php
return view('po.form', [
    'po'        => new PurchaseOrder(),
    'action'    => route('po.store'),
    'method'    => 'POST'
]);
```

Run your tests again and this time it should go green.

## Create Test for Updating

In your browser test `PurchaseOrderBrowserTest`, add the test/function:

```php
/** @test */
public function it_can_update_purchase_orders_with_valid_input_and_display_results()
{
    $createdPoList = factory(PurchaseOrder::class, 1)->create();
    $po = $createdPoList[0];

    $this->browse(function (Browser $browser) use ($po) {
        $browser
            ->visit("/po/{$po}/edit")
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

You'll probably notice that there are duplicates now with the previous test `it_can_accept_valid_purchase_orders_and_display_results`. What we can do to resolve this is to extract functions and reuse:

```php
/** @test */
    public function it_can_accept_valid_purchase_orders_and_display_results()
    {
        $this->browse(function (Browser $browser) {
            $po = $this->getPurchaseOrderStub();

            $browser->visit('/po/create');
            $this->assertFormViewContainsCorrectLabelsWith($browser);
            $this->typeInPurchaseOrderToFieldsTo($browser, $po);
            $this->assertSavedPurchaseOrderDisplaysResultsOnSubmitWith($browser, $po);
        });
    }

    /** @test */
    public function it_can_update_purchase_orders_with_valid_input_and_display_results()
    {
        $createdPoList = factory(PurchaseOrder::class, 1)->create();
        $po = $createdPoList[0];

        $this->browse(function (Browser $browser) use ($po) {
            $updatedPo = $this->getPurchaseOrderStub();

            $browser->visit("/po/{$po->id}/edit");
            $this->assertFormViewContainsCorrectLabelsWith($browser);
            $this->typeInPurchaseOrderToFieldsTo($browser, $updatedPo);
            $this->assertSavedPurchaseOrderDisplaysResultsOnSubmitWith($browser, $updatedPo);
        });
    }

    private function assertFormViewContainsCorrectLabelsWith($browser) : void
    {
        $browser
            ->assertSee('Buyer')
            ->assertSee('Supplier')
            ->assertSee('Total Cost')
            ->assertSee('Breakdown')
            ->assertSee('Purpose');
    }

    private function getPurchaseOrderStub() : array
    {
        return [
            'buyer'         => 'Ervinne Sodusta',
            'supplier'      => 'Jet Lighting LTD',
            'total_cost'    => 45000,
            'breakdown'     => 'Tube light Industrial 4w x 20, Tube light Industrial 7w x 15',
            'purpose'       => 'Replenishment'
        ];
    }

    private function typeInPurchaseOrderToFieldsTo($browser, array $po)
    {
        foreach($po as $field => $value) {
            $browser->type($field, $value);
        }
    }

    private function assertSavedPurchaseOrderDisplaysResultsOnSubmitWith($browser, array $po)
    {
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
    }
```

Then run the test with:

```bash
php artisan dusk --filter=it_can_accept_valid_purchase_orders_and_display_results
```

Which will run only the test we're interested with. We'll have to run again to make sure we didn't mess anything up.

We still have a failing test though, to make it pass, we'll have to implement the `edit` and `update` functions of `PurchaseOrderController`:

Inside `edit` function:
```php
$po = PurchaseOrder::findOrFail($id);
return view('po.form', [
    'po'        => $po,
    'action'    => route('po.update', $id),
    'method'    => 'PUT'
]);
```

Inside `update` function:
```php
$po = PurchaseOrder::findOrFail($id);
$po->fill($request->toArray());
$po->save();

return redirect()->route('po.index');
```

Now run your browser tests again and it should now be green