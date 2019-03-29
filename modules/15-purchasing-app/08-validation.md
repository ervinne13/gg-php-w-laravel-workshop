# Laravel Validation

## Custom Request Classes

Often times developers just add in validation in the controller with Laravel's validation functionality. This is not very ideal though as the controller is meant to merely dispatch requests and pass it data to different other components and not handle validation or business logic.

In Laravel, we can create our own classes based on the `Request` class and it's a very appropriate area to put the validation.

## Step 1 Create Test Case

Create a new browser test by:

```bash
php artisan dusk:make PurchaseOrderValidationBrowserTest
```

The contents must be something like:

```bash
<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\PurchaseOrderIndexPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PurchaseOrderValidationBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;
    /** @test */
    public function it_can_validate_missing_fields()
    {
        $this->browse(function (Browser $browser) {
            $userList = factory(User::class, 1)->create();
            $user = $userList[0];

            $browser
                ->loginAs($user)
                ->visit('/po/create')
                ->press('Submit')
                ->assertSee('The Supplier field is required')
                ->assertSee('The Total Cost field is required')
                ->assertSee('The Breakdown field is required')
                ->assertSee('The Purpose field is required');
        });
    }
}

```

Don't worry about getting the correct error messages yet as it's okay to not know it at first. We'll just have to run our tests and see in the screenshots later, we can just adjust the test if necessary.

## Step 2 Create the Request

```bash
php artisan make:request SavePurchaseOrderRequest
```

Inside the created `SavePurchaseOrderRequest`, write:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SavePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier'      => 'required',
            'total_cost'    => 'required|numeric',
            'breakdown'     => 'required',
            'purpose'       => 'required'
        ];
    }
}
```

Notice that we left out buyer, we'll do something about that later on.

Apply the request by replacing `Request` type hint in the `store` and `update` functions of `PurchaseOrderController` with `SavePurchaseOrderRequest`. Don't forget the use statement:

```php
//  ...
use App\Http\Requests\SavePurchaseOrderRequest;
//  ...
```

```php
//  ...
public function store(SavePurchaseOrderRequest $request)
{
//...
```

```php
//  ...
public function update(SavePurchaseOrderRequest $request)
{
//...
```

Then, we'll need to display the errors that we will be returning to the view. Add the following code to the view `resources/views/po/form.blade.php`:

```html
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

Run your tests and it should generate errors. This is because while errors are now displayed, we initially thought that Laravel capitalizes the field names. It only replaces underscores with spaces but the fields remain lower case. We'll just have to adjust the tests to:

```php
//  ...
    ->assertSee('The supplier field is required')
    ->assertSee('The total cost field is required')
    ->assertSee('The breakdown field is required')
    ->assertSee('The purpose field is required');
```

Run your tests again and it should now be all green.

## Step 3 Edge Cases

There are several edge cases we have to consider. As your activity, create tests and implement each of the following case scenarios:

- The supplier field exeeds 100 characters
- The total cost exceeds P999,999
- The total cost is 0 or negative

Tip: everything you need to add in your custom `Request` class is in Laravel's documentation [here](https://laravel.com/docs/5.8/validation).