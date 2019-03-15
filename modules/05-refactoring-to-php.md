# Refactoring our site to PHP

## Code Size

In later parts of this workshop, one of the major issues that we will tackle in software development best practices is code size. In our website earlier, it's still a bit short so it's not very noticeable but that code is dirty and not very reusable.

We'll be refactoring our plain html code to PHP so we can now "develop in `atoms`". See [atomic design](bradfrost.com/blog/post/atomic-web-design/) by Brad Frost.

## Separation of Concerns

Aside from reducing code size, we'll be focusing on separation of concerns as well. You may have noticed that we `hardcoded` our values to the front-end. We basically mixed up both `presentation` concern and `data` concern.

## Getting on Refactoring

Follow along the instructor in analyzing our webpage.

Steps

- 01 Create a new file called `index.php`.
- 02 Redirect all traffic to `index.php`. If you're using NGINX, problem already solved by following configuration provided, if apache:
    - Create a file called `.htaccess` beside `index.php`
    - See content of `.htaccess` file below
- 03 Create new folders src/views/profile, src/views/common, and src/helpers
- 04 Create helper files `string_helper_functions.php` and `view_loader_functions`.
- 05 Inside the src/views/common folder, create the following files:
    - global-meta.phtml
    - global-fonts-and-icons.phtml
    - bootstrap-css.phtml
    - bootstrap-js.phtml    
- 06 Inside the src/views/profile folder, create the following files:
    - index.phtml
    - carousel.phtml
    - skillset.phtml
    - skill.phtml
    - referrals.phtml
    - referral.phtml
    - contact-form.phtml

Follow along the instructor as he dissect our webpage, for your reference, the following should be the content of each files and should also be the output of what the instructor does.

### Index File Contents

This will be the `entry point` of our website. A pseudo controller of sorts that directs the application on what things to load and where the code should go.

File: `index.php`
```php
<?php

const LOCAL_PATH = __DIR__ . '/';

//  Bootstrap our functions
require_once(LOCAL_PATH . 'src/helpers/string_helper_functions.php');
require_once(LOCAL_PATH . 'src/helpers/view_loader_functions.php');

//  Let's not bother with routing for now and directly display the view instead.

view('profile.index');
```

### .htaccess Contents

```
RewriteEngine On
RewriteCode %(REQUEST_FILENAME) !-f
RewriteCode %(REQUEST_FILENAME) !-d
RewriteCode %(REQUEST_FILENAME) !-l
RewriteRule ^ index.php [QSA,L]
```

The instructor will explain this line by line, to follow along, see [Rewriting URLs](/learning-modules/03.1-rewriting-urls.md)

### Helper Contents

Note that we use plural in file names if the file contains a collection of what it describes. The files we have now only contain one each but for allowance purposes of allowing more functions of their kind, let's start with plural right away.

File `string_helper_functions.php`:
```php
<?php

function dot_to_path($dotNotationString) {
    return LOCAL_PATH . str_replace('.', '/', $dotNotationString);
}
```

File `view_loader_functions.php`:
```php
<?php

/**
 * Displays the specified view described in dot notation starting from the 'views' folder.
 * 
 * @param $view 
 *      The view to be displayed in dot notation.
 *      Example: displaying a view in /src/views/profile/index.php, 
 *      you may call this function with: view('profile.index')
 * @param $data
 *      Associative array representation of the variables you want
 *      your view to be able to access.
 *      Example: specifying [ 'name' => 'Ervinne' ] will enable the
 *      view to make use of a variable $name which contains the value
 *      'Ervinne'
 * 
 * @return void
 */
function view($view, $data = []) {
    //  Creates variables and encloses it in this scope
    extract($data);
    require(dot_to_path("src.views.$view") . '.phtml');
}
```

Listen to the instructor as he demonstrate the use of "scoping" the variables that will be used in each view (also as justification vs simply just requiring the files).

### Common Files Contents

File: `src/views/common/global-meta.phtml`
Justification: One place to update meta tags that apply to ALL our pages.

```html
<!-- Required meta tags -->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
```

File: `src/views/common/global-fonts-and-icons.phtml`
Justification: One place to update meta tags that apply to ALL our pages.

```html
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
```

File: `src/views/common/bootstrap-css.phtml`
Justification: Upgrading & maintaining depdencies
```html
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
```

File: `src/views/common/bootstrap-js.phtml`
Justification: Upgrading & maintaining depdencies
```html
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous" ></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
```

### Specific Page (Profile) File Contents

Here, `index` will be the main page or container of our all our sub components. Let's `stitch` first our index.phtml with the common files.

File: `src/views/profile/index.phtml`
```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <?= view('common.global-meta') ?>
        <?= view('common.bootstrap-css') ?>
        <?= view('common.global-fonts-and-icons') ?>
        <title>Ervinne Sodusta | Online Profile</title>
    </head>
    <body>
        <div class="container-fluid p-0">
            <!-- Other content will be put here after -->
        </div>

        <?= view('common.bootstrap-js') ?>
    </body>
</html>
```

### Creating the Sub Components of Specific Page (Profile)

File `src/views/profile/carousel.phtml`
```html
<div class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <picture>
                <source media="(min-width: 1200px)" srcset="/img/banner/01/default.jpg">
                <source media="(min-width: 992px)" srcset="/img/banner/01/lg.jpg">
                <source media="(min-width: 768px)" srcset="/img/banner/01/md.jpg">
                <source media="(min-width: 576px)" srcset="/img/banner/01/sm.jpg">
                <source media="(max-width: 575px)" srcset="/img/banner/01/xs.jpg">
                <img src="/img/banner/01/default.jpg" alt="Website Banner" />
            </picture>
            <div class="carousel-caption text-left d-xs-block d-sm-none">
                <h5>Ervinne Sodusta</h5>
                <p>Software Engineering Team Lead</p>
            </div>
        <div class="carousel-caption text-left d-none d-sm-block d-xl-none">
            <h4>Ervinne Sodusta</h4>
                <h5>Software Engineering Team Lead</h5>
                <p>(Products Development Team)</p>
            </div>
            <div class="carousel-caption carousel-caption-top text-left d-none d-xl-block">
                <h3>Hi there, I'm </h3>
                <h1 class="hero">Ervinne Sodusta</h1>
                <h3>Software Engineering Team Lead</h3>
                <h4>(Products Development Team)</h4>
            </div>
        </div>
    </div>
</div>
```

File `src/views/profile/skillset.phtml`
```html
<div class="row">
    <div class="col-sm text-center">
        <h1>My Skillset</h1>
        <hr />
    </div>
</div>

<div class="row">
    <div class="col-sm">
        <h3>Full Stack Development</h3>
        <p>
            Experienced full stack developer for web, desktop/standalone, and mobile applications.
        </p>
    </div>
    <div class="col-sm">
        <h3>Architecture & Resource Management</h3>
        <p>
            Experienced in software architecture design and planning, software development project planning and supervision where resources may work in parallel with each other.
        </p>
    </div>
    <div class="col-sm">
        <h3>Task & Time Management</h3>
        <p>
            Had actual field experience as a freelance software developer for 1.5 years before graduating as a computer engineering student, while maintaining academic scholarship.            
        </p>
    </div>
</div>
```

File `src/views/profile/referrals.phtml`
```html
<div class="row ">
    <div class="col-sm text-center">
        <h1>Referrals</h1>
        <hr />
    </div>
</div>

<div class="row">
    <div class="col-lg">
        <div class="media">
            <img class="align-self-start mr-3" src="https://randomuser.me/api/portraits/men/18.jpg" alt="Generic placeholder image">
            <div class="media-body">
                <h5 class="mt-0">Jacob Terry</h5>
                <p>I observe him goind the extra mile making sure his applications follow the current best practices avaiable for that tech stack. If you need someone who can write testable and maintainable software that can last years, he's the man.</p>
                <p>He really needs to work on his front-end designing skills though as he lacks a lot in that regard.</p>
            </div>
        </div>
    </div>        
    <div class="col-lg">
        <div class="media">
            <img class="align-self-start mr-3" src="https://randomuser.me/api/portraits/men/93.jpg" alt="Generic placeholder image">
            <div class="media-body">
                <h5 class="mt-0">Harold Roberts</h5>
                <p>He's our research and development person aside from his job description. He can switch from best practice to quick and dirty for a fast paced environment when trying to create demos for clients.</p>
                <p>I would recommend making use of his broad knowledge, he knows Lotus Notes, .NET, Java, JavaScript, PHP, Python and probably lots more. Just give him his materials (books, training subscriptions).</p>
            </div>
        </div>
    </div>

    <div class="col-lg">
        <div class="media">
            <img class="align-self-start mr-3" src="https://randomuser.me/api/portraits/women/27.jpg" alt="Generic placeholder image">
            <div class="media-body">
                <h5 class="mt-0">Beatrice Olson</h5>
                <p>Aside from being an overall reliable developer, he can assist you in managing junior developers. He's produced excellente junior developers that became seniors fast in our short time together.</p>
            </div>
        </div>
    </div>
</div>
```

File `src/views/profile/contact-form.phtml`
```html
<div class="text-center">
    <h1>Contact Me</h1>
</div>

<div class="form-container">
    <form>
        <div class="form-group">
            <label for="email">Your Email address</label>
            <input type="email" class="form-control" name="email" aria-describedby="email-help" placeholder="Enter email">
            <small id="email-help" class="form-text text-muted">... so I can contact you back, I'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" class="form-control" name="name" placeholder="Enter name">              
        </div>
        <div class="form-group">
            <label for="purpose">Purpose</label>
            <select class="form-control" name="purpose">
                <option value="jof">Job Offer (Freelance)</option>
                <option value="jor">Job Offer (Regular)</option>
                <option value="o">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="purpose">Message</label>
            <textarea class="form-control" name="purpose"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
```

### `Stitching` The Subcomponents to Index

File: `src/views/profile/index.phtml`
```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <?= view('common.global-meta') ?>
        <?= view('common.bootstrap-css') ?>
        <?= view('common.global-fonts-and-icons') ?>
        <title>Ervinne Sodusta | Online Profile</title>
    </head>
    <body>
        <div class="container-fluid p-0">
            <?= view('profile.carousel') ?>

            <div class="light-grey-bg p-5">
                <?= view('profile.skillset') ?>
            </div>

            <div class="light-bg p-5">
                <?= view('profile.referrals') ?>
            </div>

            <div class="light-grey-bg p-5">
                <?= view('profile.contact-form') ?>
            </div>
        </div>

        <?= view('common.bootstrap-js') ?>
    </body>
</html>
```

## Enabling Form Submission

### Cleaning up Inputs (Manually)

#### Removing/Returning HTML Entities

|Function|Description|
|--------|-----------|
|htmlentities()|Removes HTML charaters (< and > in \<html\>) and converts it to encoded version|
|html_entity_decode()|Reverse of htmlentities()|

#### Filtering Variables

Validates & sanitizes input. If you want to remove HTML tags entirely, use filtering instead of `htmlentities()`.

```php
<?php 
  
$str = "<h1>My Header</h1>"; 
$filtered = filter_var($str, FILTER_SANITIZE_STRING); 
echo $filtered; // My Header
```

`filter_var`'s second parameter is a PHP constant that enables us to dictate what kind of input are we filtering. The following are the available constants we can use:

Common filter constants:

|Filter Constant|Comments|
|---------------|-----------|
|FILTER_VALIDATE_IP||
|FILTER_VALIDATE_INT||
|FILTER_SANITIZE_STRING||
|FILTER_VALIDATE_EMAIL||
|FILTER_UNSAFE_RAW|No filtering will be done|
|FILTER_DEFAULT|If no 2nd parameter specified, same as FILTER_UNSAFE_RAW|

See [Types of filters](http://php.net/manual/en/filter.filters.php) as reference to all types of filters and filters belonging to each type.

#### Filtering HTTP Request Inputs Directly

You may use the function `filter_input()` for this.

```php
<?php

//  assuming 

$search_html = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
$search_url = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_ENCODED);

echo "You have searched for $search_html.\n";

//  Outputs: 
//  You have searched for Me &#38; son.
```