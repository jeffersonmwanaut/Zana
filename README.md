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
my-project/
├── config/
|    ├── ABAC/
|    |    └── policy.json
|    ├── com.json
|    ├── dev.json
|    ├── mode.env
|    ├── modules.json
|    └── prod.json
├── public/
|    ├── css/
|    |    └── app.css
|    ├── img/
|    ├── js/
|    |    └── app.js
|    └── app.php
├── src/
|    └── Main
|        ├── Controller/
|        ├── Entity/
|        ├── Manager/
|        ├── view/
|        └── Main.php
├── template/
|    └── partial/
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
* [Forms](#forms)

## Module

A module represents a business application task. It encapsulates the data model associated with a task and the custom code to implement the task.

Modules used in your applications must be enabled in the `config/modules.json` file.

```json
{
    "modules": [
        "Book/Book"
    ]
}
```

### Creatin a module

Let's start by creating a new class called Book:

```php
// src/Book/Book.php
namespace Book;

use Zana\Module;
use Zana\Router\Router;

class Book extends Module
{
    
}
```

Now that the module has been created, let's enable it in the `config/modules.json` file:

```json
{
    "modules": [
        "Book/Book"
    ]
}
```

The module is now ready to be used.

### Module Directory Structure

The directory structure of a module is meant to help to keep code consistent between all Zana modules, but is flexible to be adjusted if needed.

```
my-project
.
.
.
├── src/
|    └── Book/
|        ├── Controller/
|        ├── Entity/
|        ├── Manager/
|        ├── view/
|        └── Book.php
.
.
.
```

* Controller: Contains the controllers related to the module.

* Entity: Contains the entities or model classes related to the module.

* Manager: Contains the database access objects related to the module.

* view: Contains the views related to the module.


## Routing

Your application receives requests through the router. The router then assign them to related controller actions to generate the responses. The routing configuration defines which action to run for each incoming URL. It also provides other useful features, like generating SEO-friendly URLs (e.g. `/books/42` instead of `index.php?book_id=42`).

### Creating Routes

Routes can be configured in your application module using the router.

Suppose you want to define a route for the `/books` URL in your application. To do so, create a [module class](#module) like the following:

```php
// src/Book/Book.php
namespace Book;

use Zana\Module;
use Zana\Router\Router;

class Book extends Module
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
// src/Book/Book.php
namespace Book;

use Zana\Module;
use Zana\Router\Router;

class Book extends Module
{
    public function __construct()
    {
        Router::get(
            '/books/:id',
            'Book\Controller\BookController#show'
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
// src/Book/Book.php
namespace Book;

use Zana\Module;
use Zana\Router\Router;

class Book extends Module
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
// src/Book/Book.php
namespace Book;

use Zana\Module;
use Zana\Router\Router;

class Book extends Module
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

Generating URLs allows you to not write the `<a href="...">` values manually in your HTML templates. Also, if the URL of some route changes, you only have to update the route configuration and all links will be updated.

To generate a URL, you need to specify the name of the route (e.g. `BOOK`) and the values of the parameters defined by the route (e.g. `id = 42`).

#### Generating URLs in Controllers

Since your controller extends from `Zana\Controller\Controller`, you can use the `generateUrl()` static method from the `Zana\Router\Router` class.

```php
// src/Book/Controller/BookController.php
namespace Book\Controller;

use Zana\Controller;

class BookController extends Controller
{
    public function index():Page
    {
        // Generate a URL with no parameters
        $bookListPage = $this->router::generateUrl('BOOKS');

        // Generate a URL with parameters
        $bookPage = $this->router::generateUrl('BOOK', ['id' => $book->getId()]);
    }
}
```

#### Generating URLs in Templates

Instead of writing the link URLs by hand, use the `generateUrl()` static method from the `Zana\Router\Router` to generate URLs based on the routing configuration.

Later, if you want to modify the URL of a particular page, all you'll need to do is change the routing configuration, the templates will automatically generate the new URL.

```html
<div>
    <!-- src/Book/view/index.php -->
    
    <!-- Generate a URL with no parameters -->
    <a href="<?= $router::generateUrl('BOOKS') ?>">List Books</a>
    
    <!-- Generate a URL with parameters -->
    <a href="<?= $router::generateUrl('BOOK', ['id' => $book->getId()]) ?>">Show Book</a>
</div>
```

### URL Builder

The `Zana\Router\UrlBuilder` allows you to construct URLs in a more modular and flexible way, which can be useful in complex scenarios. This is a alternative approach to the `Zana\Router\Router::generateUrl()` method. For example, you could use the URL builder to generate URLs with query parameters or anchor tags.

To generate a URL like `/dashboard/123?slug=john-doe`:

```php
$urlBuilder = new UrlBuilder();
$url = $urlBuilder
    ->setRoute('DASHBOARD')
    ->setRouteParameter('id', 123)
    ->setQueryStringParameter('slug', 'john-doe')
    ->build();

echo $url;
```

URL with anchor tag like `/dashboard#top`:

```php
$urlBuilder = new UrlBuilder();
$url = $urlBuilder
    ->setRoute('DASHBOARD')
    ->setAnchor('top')
    ->build();

echo $url;
```

### Redirecting

If you want to redirect the user to another page, use the `redirect()` method from the `HttpResponse` object.

```php
// src/Book/Controller/BookController.php
namespace Book\Controller;

use Zana\Controller;
use Zana\Http\HttpResponse;

class BookController extends Controller
{
    public function index():Page
    {
        // Redirect to the book list page
        $this->httpResponse->redirect($this->router::generateUrl('BOOKS'));

        // redirects externally
        $this->httpResponse->redirect("https://example.com");
    }
}
```

## Controller

A controller is a function that is assigned a request by the router to create and return a response. The response is in fact a page object and could be in a TEXT, HTML, JSON or XML format. The controller runs whatever arbitrary logic your application needs to render the content of a page.

### Basic Controller

A controller is usually a method inside a controller class.

```php
// src/Main/Controller/MainController.php
namespace Main\Controller;

use Zana\Controller;

class MainController extends Controller
{
    public function helloWorld():Page
    {
        return $this->page->write("<h1>Hello world</h1>", PageFormat::HTML); // PageFormat is defaulted to HTML
    }
}
```

### Mapping a URL to a Controller

In order to view the result of this controller, you need to map a URL to it via a route. Routes are defined in the modules as shown below:

```php
// src/Main/Main.php
namespace Main;

use Zana\Module;
use Zana\Router\Router;

class Main extends Module
{
    public function __construct()
    {
        // ...

        Router::get(
            '/hello-world',
            'Main\Controller\MainController#helloWorld'
            'HELLO_WORLD'
        );

        // ...
    }
}
```

To see your page, go to this URL in your browser: `http://localhost/my-project/hello-world`

For more information on routing, see [Routing](#Routing).

### Rendering Templates

If you're serving HTML, you'll want to render a template. The `setView()` method renders a template and puts that content into the response for you.

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

```html
<div>
    <!-- src/Main/view/hello-world.php -->
    
    <h1>Hello world</h1>
</div>
```

If you want to use your own template, use both the `setView()` and `setTemplate()` methods. The `setTemplate()` allows you to specify the path of your custom template. We recommand you to put your templates in the `template` folder.

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
            ->setView('hello-world') // The view that contains the hello-world web page content
            ->setTemplate('my-template'); // The template of the hello-world web page, assuming that my-template is located in the template folder.
    }
    // ...
}
```

## Templates

...

## Front-end Tools: Bootstrap

...

## Configuring Zana

The configuration is made in the files stored in the `config/` folder, which has the default structure below:

```
my-project/
├── config/
|    ├── ABAC/
|    |    └── policy.json
|    ├── com.json
|    ├── dev.json
|    ├── mode.env
|    ├── modules.json
|    └── prod.json
```

* The `ABAC` folder contains the configuration of access control policies.
* The `com.json` file contains the configuration related to both development and production environments.
* The `dev.json` file contains the configuration related to the development environment only.
* The `mode.env` file contains the value of the active environment.
* The `modules.json` file contains the active modules of the application.
* The `prod.json` file contains the configuration related to the production environment only.

### Configuration Environments

You have only one application, but whether you realize it or not, you need it to behave differently at different times:

* While developing, you want to log everything and expose nice debugging tools;
* After deploying to production, you want that same application to be optimized for speed and only log errors.

A typical Zana application begins with two environments:

* dev for local development,
* prod for production servers.

### Selecting the Active Environment

Zana applications come with a file called `mode.env` located in the `config/` folder. This file is used to define the value of the active environment.

Open the `mode.env` file and edit the content to change the environment in which the application runs. For example, to run the application in production:

```txt
prod
```

### Common Configuration

In reality, each environment differs only somewhat from other. This means that all environments share a large base of common configuration, which is put in the `com.json` file.

### Configuring Environment Variables

For example, to define the database for the development environment in the `config/dev.json` file:

```json
{
    "db": {
        "mysql": {
            "host": "YOUR_HOST",
            "port": "YOUR_PORT",
            "name": "YOUR_DATABASE_NAME",
            "user": "YOUR_DATABASE_USERNAME",
            "password": "YOUR_DATABASE_USER_PASSWORD",
            "charset": "utf8",
            "collation": "utf8mb4_general_ci",
            "prefix": ""
        }
    }
}
```

### Accessing Configuration Parameters

#### Accessing in Controllers

Since your controller extends from `Zana\Controller\Controller`, you can use the `get()` static method from the `Zana\Config\Config` class.

```php
// src/Main/Controller/MainController.php
namespace Main\Controller;

use Zana\Controller;

class MainController extends Controller
{
    public function index():Page
    {
        $databaseName = $this->config::get('db.mysql.name'); // Use key path to get related value
        // ...
    }
}
```

#### Accessing in templates

```html
<div>
    <!-- src/Main/view/hello-world.php -->
    
    <h1><?= $config::get('db.mysql.name') ?></h1>
</div>
```

## Forms

### Building Forms

...

### Rendering Forms

...

### Processing Forms

...

### Validating Forms

Validation is done by adding a set of rules, called form validation rules, in the `config/com.json` file:

```json
{
    "formValidatorRules": {
        "name": "required",
        "email": "required|email",
        "password": "required|minLength:8"
    }
}
```

Now you can use the `Zana\Form\FormValidator::validate()` to validate whether the form hs been submitted with valid or invalid data.

```php
$validator = new FormValidator($formData);

if (!$validator->validate($rules)) {
    $errors = $validator->getErrors();
    // Handle errors
}
```

You can now use the `Zana\Form\FormValidator::validate()` method with custom error messages like this:

```php
$validator = new FormValidator($formData);
$customErrorMessages = [
    'required' => 'The {field} field is mandatory.',
    'email' => 'Invalid email address for {field}.',
    'minLength' => 'Minimum length for {field} is {length} characters.',
    'maxLength' => 'Maximum length for {field} is {length} characters.',
];

if (!$validator->validate($rules, $customErrorMessages)) {
    $errors = $validator->getErrors();
    // Handle errors
}
```

### Other Form Features

...

## Creators

**Jefferson Mwanaut**

- <https://github.com/jeffersonmwanaut>
- <https://www.linkedin.com/in/jeffersonmwanaut>
