# Framework Structure

In summary, the MVC parts will be the following:

Model: Anywhere in the `/app`, but we'll use `/models/app`

View: `/resources`

Controller: `/app/Http/Controllers`

## Where Is The Models Directory?

When getting started with Laravel, many developers are confused by the lack of a `models` directory. However, the lack of such a directory is intentional. We find the word "models" ambiguous since it means many different things to many different people. Some developers refer to an application's "model" as the totality of all of its business logic, while others refer to "models" as classes that interact with a relational database.

## The other folders

We can check out the details of the other folders by refering to the documentation [here](https://laravel.com/docs/5.8/structure)