# Creating our Simple Purchasing Project

Requirements:
- Create a simple form that describes a simple purchasing form.
- In the future, we want a full table for our purchasing details, for now, we can put the items to be purchased in a text area.
- The form will have the following functions:
    - Save, Update, Delete, View
    - Send for Approval
    - Approve 1
    - Approve 2
    - Post
- The functions Send for Approval onwards will be done after a do it yourself session.

## Steps & Features

- [Creating the Purchase Order Form](/15-purchasing-app/01-form.md)
- [Improving our Tests](/15-purchasing-app/02-improving-tests.md)
- [Displaying Purchase Orders](/15-purchasing-app/03-displaying-purchase-orders.md)

## DIY
Now that you've done create and viewing of all documents. You'll have to fill in the view of each purchase order, update, and delete.

Tips:
- To update, you'll have to change the form method from POST to PUT. You should be able to do this without creating multiple `form.blade.php`.
- To delete, you'll have to do this in JavaScript. Add your own JavaScript file and do an ajax request with the method DELETE when a "Delete" button is clicked.