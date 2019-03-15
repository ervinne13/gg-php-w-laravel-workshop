# Bootstrap Basics

### Getting Bootstrap by CDN

The good thing about CDN is that we reduce the load in our hosting servers by delegating the hosting of our dependencies, which in this case is bootstrap.

Create a new file called `index.html` that contains the following.

File `index.html`:
```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS -->
        <link
        rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous"
        />

        <title>Hello, bootstrap!</title>
    </head>
    <body>
        <h1>Hello, bootstrap!</h1>

        <script
        src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"
        ></script>
        <script
        src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"
        ></script>
    </body>
</html>
```

(Listen to instructor for the breakdown)

### Bootstrap Basics

The instructor will walk you through the following. Use these links to open up the documentation.

#### 01 - Containers
Follow [this link to the documentation](https://getbootstrap.com/docs/4.0/layout/overview/).

Quick Notes:

Class `.container` is for traditional websites that has equal margin on both left and right while you will likely `.container-fluid` for admin/cms side of things where you want the full width.

| Container Class   | Common use case |
|-------------------|-------------------------------|
| .container        | Classic style websites, blogs |
| .container-fluid  | Modern style websites, admin, anything full width |


#### 02 - Grid System

Follow [this link to the documentation](https://getbootstrap.com/docs/4.0/layout/grid/).

Some Notes:
Bootstrap 3's grid system is very different from bootstrap 4's. Bootstrap 4 uses a more flexible grid system by using the modern css's `flex box` which you can [read more from here](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout/Basic_Concepts_of_Flexbox).

Rough estimate of responsive breakpoints

| Responsive Break Point | Close Sample             |
|------------------------|--------------------------|
| xs (575.98px max) | Iphone 5s and smaller devices |
| sm (767.98px max) | Iphone 7 size devices |
| md (991.98px max) | Tablets |
| lg (1199.98px max) | Desktop Computers |

### Let's Do it Ourselves: Banner + Testimonials

We'll be making use of `container` and the `grid system`.

__Best Practices!__
    
- Mobile First
- Use different assets per device size

Download a banner of your choice and create smaller copies of it. Do this at least twice for `sm` and `xs` devices and for `lg` and `md` desktops/tablets. (You can use paint for this and reduce the size by percentage).

Enable mobile building
Press `F12` and press `Ctrl` + `Shift` + `M` or find and click the `Toggle device toolbar`.

#### Steps

- 1   Create folder img/banner/01
- 2   Copy the banner of your choice
- 3   Create smaller copies according our responsive breakpoint table above
- 4   Create a carousel using: [link to bootstrap documentation](https://getbootstrap.com/docs/4.0/components/carousel/)

Tip: add class `.p-0` to remove padding completely, we'll use this for our `.container-fluid` container.

Tip: use `<picture>` with media sources to show/hide image depending on the screen size. Use [container](https://getbootstrap.com/docs/4.0/layout/overview/)'s media sizes as reference.

Tip: Choosing Fonts, you may use [google fonts](https://fonts.google.com/), we'll be using [open sans](https://fonts.google.com/specimen/Open+Sans).

If you want to know more about typography (what's Serif, Sans Serif,etc.) [click here](https://www.youtube.com/watch?v=sByzHoiYFX0).