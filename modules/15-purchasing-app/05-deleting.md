# Deleting Purchase Orders

## Step 1 Setting up our Test

Add a new test in `PurchaseOrderBrowserTest` called `it_can_delete_purchase_orders`:

```php
/** @test */
public function it_can_delete_purchase_orders()
{
    $createdPoList = factory(PurchaseOrder::class, 1)->create();
    $po = $createdPoList[0];

    $this->browse(function (Browser $browser) use ($po) {        
        $actionLinkSelector = "[action='delete-po'][data-id='{$po->id}']";
        $browser->visit('/po');
        $this->assertSeePurchaseOrderRowOn($browser, $po);

        $browser
            ->click($actionLinkSelector)
            ->waitForReload()
            ->assertUrlIs(url("/po"));

        $this->assertDontSeePurchaseOrderRowOn($browser, $po);
    });
}

private function assertSeePurchaseOrderRowOn($browser, $po)
{
    $browser
        ->with('.table', function($table) use ($po) {
            $table
                ->assertSee($po['buyer'])
                ->assertSee($po['supplier'])
                ->assertSee($po['purpose']);
        });
}

private function assertDontSeePurchaseOrderRowOn($browser, $po)
{
    $browser
        ->with('.table', function($table) use ($po) {
            $table
                ->assertDontSee($po['buyer'])
                ->assertDontSee($po['supplier'])
                ->assertDontSee($po['purpose']);
        });
}
```

You should notice that the content of the function `assertSeePurchaseOrderRowOn` is duplicate code and is actually being used by `assertSavedPurchaseOrderDisplaysResultsOnSubmitWith`.

We can refactor it to use the new function and it should result in the following:

```php
private function assertSavedPurchaseOrderDisplaysResultsOnSubmitWith($browser, array $po) : void
{
    $browser
        ->press('Submit')
        ->on(new PurchaseOrderIndexPage());
        
    $this->assertSeePurchaseOrderRowOn($browser, $po);
}
```

Run your test and it should have 1 failing test and the rest should still pass.

## Deleting Purchase Orders on Request

In the purchase order controller, update the `destroy` function and add the contents:

```php
$po = PurchaseOrder::findOrFail($id);
$po->delete();
```

For this to work, we'll have to actually generate `delete` requests from the front-end.

## Basic AJAX with Axios

In your view `resources/views/po/index.blade.php`, add the following script in your content:

```js
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    initializeEvents();
});

function initializeEvents() {
    const deleteActions = document.querySelector('[action=delete-po]')

    if (deleteActions) {
        deleteActions.addEventListener('click', function(el) {
            const poId = this.getAttribute('data-id');
            deletePurchaseOrderWithId(poId);
        });
    }
}

function deletePurchaseOrderWithId(poId) {
    const url = `/po/${poId}`;
    axios.delete(url)
        .then((response) => {
            console.log(response);
            window.location.reload();
        });
}
</script>
```

This wont work right away though as we need axios to make this work, we can either depend on CDN like last time, or we utilize what we already have inside `resources/js`.

## Compiling Scripts

Run the following command to install our front-end script dependencies:

```bash
npm install
```

Then generate our assets (dev only) using:

```bash
npm run dev
```

This will generate the files `public/js/app.js` and `public/css/app.css`. Follow along the instructor as he discusses how these scripts came to be. Ideally, these scripts should be gitignored too!

Import at least our script by adding the following in our layout at `resources/views/layout/default.blade.php`:

```html
<script src="{{asset('js/app.js')}}"></script>
```

The `Delete` place holder action in the actions column in our `index.blade.php` file should also be changed to an actual action link:

```html
<a action="delete-po" data-id="{{$po->id}}" href="javascript:;">Delete</a>
```

Now try running your tests again and it should now turn green.