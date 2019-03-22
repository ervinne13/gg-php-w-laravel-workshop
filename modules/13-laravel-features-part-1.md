# Laravel Features (Part 1)

## Creating a Project

Let's first create a new project:

```bash
composer create-project --prefer-dist laravel/laravel purchasing
```

You may also install a helper for creating new laravel projects by:

```bash
composer global require laravel/installer
```

Then the previous command can then be changed to:

```bash
laravel new purchasing
```

## Overview

The instructor will walk you through the Laravel documentation first, afterwards, we'll walk through everything again but the next time, by creating a purchasing module for our project.

- [Routing](https://laravel.com/docs/5.8/routing)
- [Middleware](https://laravel.com/docs/5.8/middleware)
- [Controllers](https://laravel.com/docs/5.8/controllers)
- [Views](https://laravel.com/docs/5.8/views)
- [Migration](https://laravel.com/docs/5.8/migrations) & [Seeding](https://laravel.com/docs/5.8/seeding)
- [Eloquent](https://laravel.com/docs/5.8/eloquent)
- [Testing]

## Creating our Simple Purchasing Project

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

### Step 1 Define our Test

