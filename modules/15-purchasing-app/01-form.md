
# Creating the Purchase Order Form

## Step 1 Creating the Controllers & Routes

We'll need to create a form that can accept purchase orders. Since this feature is "CRUD", let's create a resource route & controller for it:

```bash
php artisan make:controller PurchaseOrderController --resource
```

Then create our route for it by adding:

```php
Route::resource('/po', 'PurchaseOrderController');
```

in the `/routes/web.php` file.

Let's first design a page where we can input our forms. The following fields will be used in the form:

- Buyer (After implementing login later, should be automated, this is your name)
- Supplier
- Total Cost
- Breakdown (text area, will be a table in the future)
- Purpose (text area)

Create a new folder in `/resources/views` called `po` and inside it, create a new file `form.blade.php`.

File `form.blade.php`:

```html
@extends('layout.default')

@section('content')
<form action="/po" method="POST">
    {{csrf_field()}}
    <div class="form-group">
        <label>Buyer</label>
        <input name="buyer" type="text" class="form-control" placeholder="The name of the creator of this PO">        
    </div>
    <div class="form-group">
        <label>Supplier</label>
        <input name="supplier" type="text" class="form-control" placeholder="The supplier we will buy from">        
    </div>
    <div class="form-group">
        <label>Total Cost</label>
        <input name="total_cost" type="number" class="form-control">        
    </div>
    <div class="form-group">
        <label>Breakdown</label>
        <textarea name="breakdown" type="text" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label>Purpose</label>
        <textarea name="purpose" type="text" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection
```

Then let's create the layout that the form and other views may use. Create a folder called `layout` in `/resources/views` and create a new file `default.blade.php`.

File  `default.blade.php`:
```html
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <link href="{{asset('css/app.css')}}" rel="stylesheet">      
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>
        <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
    </body>
</html>
```

To include bootstrap, let's first generate our assets by:

```bash
npm install && npm run dev
```

Now that we have a form, we can create a new feature. Create a test that describes the feature:

## Step 2 Defining Our Feature by Defining a Test

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderFeatureTest extends TestCase
{
    use RefreshDatabase;

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
        $response = $this->json('POST', '/po', $po);
        $response->assertRedirect('/po');
        $this->assertDatabaseHas('purchase_orders', $po);
    }
}
```

With this, we now have a failing test that we need to satisfy. We should start with creating a database. 

## Step 3 Connecting to a Database and Creating the Table(s)

In your workbench or phpmyadmin, create a database called `gg_purchasing`. Then supply your connection details in your .env file:

Example:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gg_purchasing
DB_USERNAME=root
DB_PASSWORD=
```

Run the test again and the failure message should change to "Base table or view not found: 1146 Table 'gg_purchasing.purchase_orders' doesn't exist".

Let's now create our database by creating a migration:

```bash
php artisan make:migration create_purchase_orders_table
```

Add in the following as the table's columns:

```php
$table->bigIncrements('id');
$table->string('buyer', 100);
$table->string('supplier', 100);
$table->decimal('total_cost', 8, 2);
$table->text('breakdown');
$table->text('purpose');
$table->timestamps();
```

Then migrate just so we can see our table created:

```bash
php artisan migrate
```

## Step 4 Creating our model

Let's create our model for purchase orders by:

```bash
php artisan make:model Models/PurchaseOrder
```

We'll then have to supply our `fillables`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'buyer',    //  to be removed later after implementing login
        'supplier',
        'total_cost',
        'breakdown',
        'purpose'
    ];
}

```

## Step 5 Saving a Purchase Order

Finally, we'll use the model to save the data from the request, update the `store` method to be like below:

```php
public function store(Request $request)
{
    $po = new PurchaseOrder();
    $po->fill($request->toArray());
    $po->save();

    return redirect()->route('po.index');
}
```

Make sure to `use` the `PurchaseOrder` by adding a use statement below your `namespace`;

```php
<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;// here!
use Illuminate\Http\Request;
```

## Step 6 Turning the Test to Green

Run your tests and it should now be green:

```bash
vendor/bin/phpunit
```

Note that if you see more than 1 tests, remove `/tests/Feature/ExampleTest.php` and `/tests/Unit/ExampleTest.php`.

In our advanced classes, we'll be updating our tests to include negative scenarios and apply validations.

Test your real form in the browser and you should be able to see your input in the database.