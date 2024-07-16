<p align="center">
    <img src="public/img/icon.png" alt="Zana logo" width="200" height="165">
</p>

<h3 align="center">Zana</h3>

# Zana

Turnkey PHP framework offering components that serve as a foundation and toolbox for your web application.

## Table of contents

- [Technical requirements](#technical-requirements)
- [Quick start](#quick-start-up)
- [Project structure](#project-structure)
- [What's next](what's-next)
- [Creators](#creators)

## Technical requirements

Before you create your first Zana application you must:
* Install PHP 8 or higher;
* [Install composer](https://getcomposer.org/download/), which is used to install PHP packages.

## Quick start-up

### Installation

Download the compressed file, Unzip it directly to the root of your project and quickly [create your first page](#hello-world).

### Hello world

Creating a new page is a two-step process:
1. Create a route: A route is the URL (e.g. `/hello-world`) to your page and points to a controller.
2. Create a controller: A controller is the PHP function you write that renders the page. You take the incoming request information and use it to create a Zana page object, which can hold HTML, TEXT, JSON or XML content.

#### Create a route

Open the Main module `src/Main/Main.php` and add a new route `/hello-world` for the Hello world example.

```php
// src/Main/Main.php
namespace Main;

use Zana\Module;
use Zana\Router\Router;

class Main extends Module
{
    public function __construct()
    {
        Router::get(
            '/hello-world', // The route or URI
            'Main\Controller\MainController#helloWorld', // The controller function that will build the page
            'HELLO_WORLD' // The route name
        );
        // ...
    }
}
```

#### Create a controller

1. Open the Main controller `src/Main/Controller/MainController.php` and add a new function `helloWorld()` that will render the page.

```php
// src/Main/Controller/MainController.php
namespace Main;

use Zana\Controller;

class MainController extends Controller
{
    public function helloWorld()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Hello world" // Document title to display in the browser tab
            ])
            ->setView('hello-world'); // The view that contains the hello-world web page content
    }
    // ...
}
```

2. Create the view `src/Main/view/hello-world.php` that will be used by the controller to render the page.
```php
<h1>Hello world!</h1>
```

That's it! Now open your browser and navigate to `http://localhost/my-project/hello-world`. If everything is working, you will see the hello world page.

## Project structure

```
.
├── config/
|    ├── ABAC/
|    ├── com.php
|    ├── dev.php
|    ├── mode.txt
|    ├── modules.php
|    └── prod.php
├── public/
|    ├── css/
|    ├── img/
|    ├── js/
|    └── app.php
├── src/
├── template/
├── vendor/
└── .htaccess
```

* config: Contains configuration.

* public: This is the document root for your project: you put any publicly accessible files here.

* src: All your PHP code goes here.

* template: All your templates go here.

* vendor: Zana code and third-party libraries live here! Third-party libraries are downloaded via [Composer](https://getcomposer.org/download/)

## What's next

Now that you've learned a new way of building beautiful and functional applications, finish mastering the fundamentals by reading the following articles:
* [Module](#module)
* [Routing](#routing)
* [Controller](#controller)
* [Templates](#templates)
* [Front-end Tools: Bootstrap](#front-end-tools-bootstrap)
* [Configuring Zana](#configuring-zana)

## Module

A module represents a business application task. It encapsulates the data model associated with a task and the custom code to implement the task.

Modules used in your applications must be enabled in the `config/modules.php` file.

```php
// config/modules.php
return [
    'modules' => [
        \Blog\Blog::class
    ]
];
```

### Creatin a module

Let's start by creating a new class called Blog:

```php
// src/Blog/Blog.php
namespace Blog;

use Zana\Module;
use Zana\Router\Router;

class Blog extends Module
{
    
}
```

Now that the module has been created, let's enable it:

```php
// config/modules.php
return [
    'modules' => [
        \Blog\Blog::class
    ]
];
```

The module is now ready to be used.

### Module Directory Structure

The directory structure of a module is meant to help to keep code consistent between all Zana modules, but is flexible to be adjusted if needed.

```
.
.
.
├── src/
.    └── Blog/
.        ├── Controller/
.        ├── Entity/
         ├── Manager/
         ├── view/
         └── Blog.php
```

* Controller: Contains the controllers related to the module.

* Entity: Contains the entities or model classes related to the module.

* Manager: Contains the database access objects related to the module.

* view: Contains the views related to the module.


## Routing

When your application receives a request, it calls a controller action to generate the response. The routing configuration defines which action to run for each incoming URL. It also provides other useful features, like generating SEO-friendly URLs (e.g. `/books/42` instead of `index.php?book_id=42`).

### Creating Routes

Routes can be configured in your application module using the router.

Suppose you want to define a route for the `/books` URL in your application. To do so, create a [module class](#module) like the following:

```php
// src/Blog/Blog.php
namespace Blog;

use Zana\Module;
use Zana\Router\Router;

class Blog extends Module
{
    public function __construct()
    {
        Router::get(
            '/books', // The route or URI
            'Main\Controller\MainController#index', // The controller's method that will build the page
            'BOOKS' // The route name
        );
        // ...
    }
}
```

This code defines a route called `BOOKS` that matches when the user requests the `/books` URL. When the match occures, the application runs the `index` method of the `MainController` class.

The query string of a URL is not considered when matching routes. In this example, URLs like `/books?author=gt90` will also match the `BOOKS` route.

If you define multiple routes with the same route name, Zana only consider the first route, ignoring all the others.

The route name `BOOKS` is not important for now, but will be essential later when [generating URLs](#generating-urls). You only have to keep in mind that each route name must be unique in the application.

### Route Parameters

The previous examples defined routes where the URL never changes (e.g. `/books`). However, it's common to define routes where some parts are variable. For example, the URL to display a specific book will probably include the title or id (e.g. `/books/all-about-zana` or `/books/42`).

In Zana routes, variable parts start with the colon sign `:`. For example, the route to display a specific book contents is defined as `/books/:id`.

```php
// src/Blog/Blog.php
namespace Blog;

use Zana\Module;
use Zana\Router\Router;

class Blog extends Module
{
    public function __construct()
    {
        Router::get(
            '/books/:id',
            'Book\Controller\BookController#index'
        );
        // ...
    }
}
```

The name of the variable part (`:id` in this example) is used to create a PHP variable where that route content is stored and passed to the controller. If a user visits the `/books/42` URL, Zana executes the `show()` method in the `BookController` class and passes a `$id = 42` argument to the `show()` method.

Routes can define any number of parameters, but each of them can only be used once on each route (e.g. `/books/:id/page/:pageNumber`).

#### Parameters Validation

Imagine that your application has a show route (URL: `/books/:id`) and a list route (URL: `/books/:author`). Given that route parameters accept any value, there's no way to differentiate both routes.

If the user requests `/books/gt90`, both routes will match and Zana will use the route which was defined first. To fix this, add some validation to the :id parameter using the requirements option:

```php
// src/Blog/Blog.php
namespace Blog;

use Zana\Module;
use Zana\Router\Router;

class Blog extends Module
{
    public function __construct()
    {
        Router::get(
            '/books/:id',
            'Book\Controller\BookController#show'
        )->with('id', '\d+');

        Router::get(
            '/books/:author',
            'Book\Controller\BookController#list'
        );
        // ...
    }
}
```

The `with` option defines the PHP regular expressions that route parameters must match for the entire route to match.

### Route Aliasing

Route alias allow you to have multiple name for the same route:

```php
// src/Blog/Blog.php
namespace Blog;

use Zana\Module;
use Zana\Router\Router;

class Blog extends Module
{
    public function __construct()
    {
        Router::get(
            '/books/:id',
            'Book\Controller\BookController#show'
            'BOOK'
        )->with('id', '\d+');

        Router::get(
            '/books/:id',
            'Book\Controller\BookController#show'
            'SHOW_BOOK'
        )->with('id', '\d+');
        // ...
    }
}
```

In this example, both `BOOK` and `SHOW_BOOK` routes can be used in the application and will produce the same result.

### Generating URLs

...


## Controller

...

## Templates

...

## Front-end Tools: Bootstrap

...

## Configuring Zana

...

## Creators

**Jefferson Mwanaut**

- <https://github.com/jeffersonmwanaut>
- <https://www.linkedin.com/in/jeffersonmwanaut>
