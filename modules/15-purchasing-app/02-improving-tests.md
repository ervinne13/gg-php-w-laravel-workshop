# Improving our Test

## Step 1 Install Laravel Dusk

Our tests is very limited, we're only really testing for the actual request. But if we messed something up in the view (like forget a csrf token), the test will still pass yet the actual behevior is that Laravel will return an error status 419.

To resolve this, we'll use `Laravel Dusk`. In your terminal:

```bash
composer require --dev laravel/dusk
```

Then:

```bash
php artisan dusk:install
```

## Step 2 Create a new Browser Test

Then let's create a new test with:

```bash
php artisan dusk:make PurchaseOrderBrowserTest
```

Inside the test, write the code:

```php
<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PurchaseOrderBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_accept_valid_purchase_orders()
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

            $browser->press('Submit');
            $this->assertDatabaseHas('purchase_orders', $po);
        });
    }    
}
```

Then run the test using:

```bash
php artisan dusk
```

Browser tests are, however, very slow. This is because Laravel really opens a browser on the background and hosts a server. Things here are really simulated actions that are now very human-like. Writing feature and unit tests will still be valid as you want to be able to run quick tests first, then when you are doing regression, that's when you run browser tests.

WARNING! Since browser tests are really simulated actions, these tests will touch your database. Currently, the only way to work around this is to actually create a separate database and change your setup in .env before and after browser tests.