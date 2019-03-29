# Datatables

## Using Yajra Datatables

Install yajra datatables with:

```bash
composer require yajra/laravel-datatables
```

... and then publish it's configuration and assets

```bash
php artisan vendor:publish --tag=datatables
php artisan vendor:publish --tag=datatables-buttons
```

You'll also notice that the datatables-buttons is not in the documentation, Yajra seems to neglect the documentation of his work so consider contributing to the repo.

## Creating our DataTable Service

```bash
php artisan datatables:make PurchaseOrderDataTable
```

Configure the created service by selecing all columns and replacing the default `User` model with `PurchaseOrder`.

```php
<?php

namespace App\DataTables;

use App\User;
use App\Models\PurchaseOrder;
use Yajra\DataTables\Services\DataTable;

class PurchaseOrderDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('action', function(PurchaseOrder $po) {
                return view('datatables.actions.purchase-order', ['po' => $po]);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PurchaseOrder $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PurchaseOrder $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['width' => '100px'])
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return ['id', 'buyer', 'supplier', 'total_cost', 'purpose'];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'PurchaseOrder_' . date('YmdHis');
    }
}


```

Generate the action column view we specified in the `datatable` function and create a new file `resources/views/datatables/actions/purchase-order.blade.php`:

```html
<a action="view-po" data-id="{{$po->id}}" href="{{route('po.show', $po->id)}}">View</a>
<a action="edit-po" data-id="{{$po->id}}" href="{{route('po.edit', $po->id)}}">Edit</a>
<a action="delete-po" data-id="{{$po->id}}" href="javascript:;">Delete</a>
```

Update our view `resources/views/po/index.blade.php` to use Yajra Datatables:

```html
@extends('adminlte::page')

@section('title', 'Purchase Orders | Listing')

@section('js')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="/vendor/datatables/buttons.server-side.js"></script>
{!! $dataTable->scripts() !!}

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
@stop

@section('content')
{!! $dataTable->table() !!}
@endsection
```

And finally, inject the service in our controller's `index` function and use it to return the view:

```php
public function index(PurchaseOrderDataTable $dataTable)
{
    return $dataTable->render('po.index');
}
```

Now run your tests and you'll see that there are lots of errors all over the place. Majority of it is merely caused by the view not loaded yet when tests are run. DataTables uses Ajax to perform display.

To resolve this, simply add:

```php
///...
->waitUntilMissing('#dataTableBuilder_processing')
```

To each browser tests that navigates and looks for things in the `/po` route and find things inside the table. Another issue is that our scripts are being executed before the table rows are exiting in the dom. This causes the delete action to fail.

The solution is to listen to `click` events and test there if we matched the target element that's clicked. This way, the event is attached in the actual click event and not on the non existent elements.

The `initializeEvents` function in `index.blade.php` should now be refactored to
```js
function initializeEvents() {
    document.addEventListener('click',function(e){
        if(e.target && e.target.getAttribute('action') === 'delete-po'){
            const poId = e.target.getAttribute('data-id');
            deletePurchaseOrderWithId(poId);
        }
    });
}
```

Now run your tests and it should now result in all green.