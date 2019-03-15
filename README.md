# Ground Gurus: PHP Fundamentals with Laravel

Workshop for teaching PHP from fundamentals going to Laravel development.

## What You'll Need For This Workshop

Downloadables:
- Laragon [Download Here](https://laragon.org/download/index.html)
- Composer [Download Here](https://getcomposer.org/download/)
- Git [Download Here](https://git-scm.com/downloads)
- NodeJS [Download Here](https://nodejs.org/en/download)
- (Optional but recommended) MySQL Workbench (Make sure to download workbench only, not the complete MySQL with server as Laragon already has MySQL) [Download Here](https://dev.mysql.com/downloads/workbench/)

Others:
- Prepare your professional profile picture (can be 250px x 250px, as long as it's square).
- Prepare a short description about your self.
- Prepare 3 things you're good with.
- Ask at least 3 of your colleagues, teachers, or friends for referrals and short statement about you and why you should be hired. Ask them their professional profile pictures as well.

## Checklist

Let's make sure everything works well. Open up your terminal.

Testing PHP

```bash
#### Run
php -v

#### Outputs something like:
PHP 7.2.15-0ubuntu0.18.10.1 (cli) (built: Feb  8 2019 14:54:22) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies
    with Zend OPcache v7.2.15-0ubuntu0.18.10.1, Copyright (c) 1999-2018, by Zend Technologies
```

Testing Composer
```bash
#### Run
composer -v

#### Outputs something like:
   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/
                    /_/
Composer version 1.8.4 2019-02-11 10:52:10
... more things printed here
```

Testing Git
```bash
#### Run
git --version

#### Outputs something like:
git version 2.19.1
```

Testing Node & NPM
```bash
#### Run
node -v
#### Outputs something like:
v10.15.3

#### Run
npm -v
#### Outputs something like:
6.4.1
```

If any of the command results in `command not found` or anything similar, please refer back to the appropriate installation procedure.

## The Workshop Contents:

### The fundamentals
- 01 [Your First Web Page Published](/modules/01-first-web-page.md)
- 02 [Source Control Management](/modules/02-git.md)

### Getting on Web Development
- 03 [Boostrap Basics](/modules/03-bootstrap-basics.md)
- 04 [PHP Basics](/modules/04-php-basics.md)
- 05 [Refactoring to PHP](/modules/05-refactoring-to-php.md)
- 05.1 [Redirecting to index.php](/modules/05.1-rewrite-engine.md)
- 06 [OOP in PHP](/modules/06-oop.md)
- 07 [Composer & PSR 4](/modules/07-composer-and-psr-4.md)
- 08 [Refactoring to using Blade](/modules/08-composer-and-psr-4.md)
- 09 [SQL Basics & Intro to PDO](/modules/09-pdo.md)