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
- [What's next](#whats-next)
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
|    ├── Main
|    |   ├── Controller/
|    |   ├── Entity/
|    |   ├── Manager/
|    |   ├── view/
|    |   └── Main.php
|    └── MyModule
|        ├── Controller/
|        |   └── MyController.php
|        ├── Entity/
|        |   └── MyEntity.php
|        ├── Manager/
|        |   └── MyManager.php
|        ├── view/
|        |   └── my-view.php
|        └── MyModule.php
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
* [Databases and Data Access Objects](#databases-and-data-access-objects)

## Module

A module represents a business application task. It encapsulates the data model associated with a task and the custom code to implement the task.

Modules used in your applications must be enabled in the `config/modules.json` file.

```json
{
    "modules": [
        "MyModule/MyModule"
    ]
}
```

### Creatin a module

Let's start by creating a new class called Book:

```php
// src/MyModule/MyModule.php
namespace Book;

use Zana\Module;
use Zana\Router\Router;

class MyModule extends Module
{
    
}
```

Now that the module has been created, let's enable it in the `config/modules.json` file:

```json
{
    "modules": [
        "MyModule/MyModule"
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
|    └── MyModule/
|        ├── Controller/
|        ├── Entity/
|        ├── Manager/
|        ├── view/
|        └── MyModule.php
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
// src/MyModule/MyModule.php
namespace MyModule;

use Zana\Module;
use Zana\Router\Router;

class MyModule extends Module
{
    public function __construct()
    {
        Router::get(
            '/books', // The route or URI
            'MyModule\Controller\MyController#index', // The controller's method that will build the page
            'BOOKS' // The route name
        );
        // ...
    }
}
```

This code defines a route called `BOOKS` that matches when the user requests the `/books` URL. When the match occures, the application runs the `index` method of the `MyController` class.

The query string of a URL is not considered when matching routes. In this example, URLs like `/books?author=gt90` will also match the `BOOKS` route.

If you define multiple routes with the same route name, Zana only consider the first route, ignoring all the others.

The route name `BOOKS` is not important for now, but will be essential later when [generating URLs](#generating-urls). You only have to keep in mind that each route name must be unique in the application.

### Route Parameters

The previous examples defined routes where the URL never changes (e.g. `/books`). However, it's common to define routes where some parts are variable. For example, the URL to display a specific book will probably include the title or id (e.g. `/books/all-about-zana` or `/books/42`).

In Zana routes, variable parts start with the colon sign `:`. For example, the route to display a specific book contents is defined as `/books/:id`.

```php
// src/MyModule/MyModule.php
namespace MyModule;

use Zana\Module;
use Zana\Router\Router;

class MyModule extends Module
{
    public function __construct()
    {
        Router::get(
            '/books/:id',
            'MyModule\Controller\MyController#show'
        );
        // ...
    }
}
```

The name of the variable part (`:id` in this example) is used to create a PHP variable where that route content is stored and passed to the controller. If a user visits the `/books/42` URL, Zana executes the `show()` method in the `MyController` class and passes a `$id = 42` argument to the `show()` method.

Routes can define any number of parameters, but each of them can only be used once on each route (e.g. `/books/:id/page/:pageNumber`).

#### Parameters Validation

Imagine that your application has a show route (URL: `/books/:id`) and a list route (URL: `/books/:author`). Given that route parameters accept any value, there's no way to differentiate both routes.

If the user requests `/books/gt90`, both routes will match and Zana will use the route which was defined first. To fix this, add some validation to the :id parameter using the requirements option:

```php
// src/MyModule/MyModule.php
namespace Book;

use Zana\Module;
use Zana\Router\Router;

class MyModule extends Module
{
    public function __construct()
    {
        Router::get(
            '/books/:id',
            'MyModule\Controller\MyController#show'
        )->with('id', '\d+');

        Router::get(
            '/books/:author',
            'MyModule\Controller\MyController#list'
        );
        // ...
    }
}
```

The `with` option defines the PHP regular expressions that route parameters must match for the entire route to match.

### Route Aliasing

Route alias allow you to have multiple name for the same route:

```php
// src/MyModule/MyModule.php
namespace Book;

use Zana\Module;
use Zana\Router\Router;

class MyModule extends Module
{
    public function __construct()
    {
        Router::get(
            '/books/:id',
            'MyModule\Controller\MyController#show'
            'BOOK'
        )->with('id', '\d+');

        Router::get(
            '/books/:id',
            'MyModule\Controller\MyController#show'
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
// src/MyModule/Controller/MyController.php
namespace Book\Controller;

use Zana\Controller;

class MyController extends Controller
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
    <!-- src/MyModule/view/index.php -->
    
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
// src/MyModule/Controller/MyController.php
namespace Book\Controller;

use Zana\Controller;
use Zana\Http\HttpResponse;

class MyController extends Controller
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
// src/MyModule/Controller/MyController.php
namespace Main\Controller;

use Zana\Controller;

class MyController extends Controller
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
// src/MyModule/MyModule.php
namespace Main;

use Zana\Module;
use Zana\Router\Router;

class MyModule extends Module
{
    public function __construct()
    {
        // ...

        Router::get(
            '/hello-world',
            'MyModule\Controller\MyController#helloWorld'
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
// src/MyModule/Controller/MyController.php
namespace Main;

use Zana\Controller;

class MyController extends Controller
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
    <!-- src/MyModule/view/hello-world.php -->
    
    <h1>Hello world</h1>
</div>
```

If you want to use your own template, use both the `setView()` and `setTemplate()` methods. The `setTemplate()` allows you to specify the path of your custom template. We recommand you to put your templates in the `template` folder.

```php
// src/MyModule/Controller/MyController.php
namespace MyModule;

use Zana\Controller;

class MyController extends Controller
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

A template is the best way to organize and render HTML from inside your application. Zana rely on PHP capabilities for creating templates. The following is an example of template.

```html
<!DOCTYPE html>
<html>
    <head>
        <title><?= $dTitle ?></title>
    </head>
    <body>
        <main><?= $content ?></main>
    </body>
</html>
```

### Creating Templates

First, you need to create a new file in the `template/` folder to store the template.

```html
<!-- template/my-template.php -->
<!DOCTYPE html>
<html>
    <head>
        <title><?= $dTitle ?></title>
    </head>
    <body>
        <main><?= $content ?></main>
    </body>
</html>
```

Second, you need to create a new file in your module `view/` folder to hold the view contents.

```html
<!-- src/MyModule/view/my-view.php -->
<h1>Welcome <?= $firstName ?>!</h1>
```

Then, create a controller that renders the view and the template and passes to it the needed variables:

```php
// src/MyModule/Controller/MyController.php
namespace MyModule;

use Zana\Controller;

class MyController extends Controller
{
    public function welcome()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Welcome" // Document title to display in the browser tab
                'firstName' => $firstName // Variable $firstName passed to the view
            ])
            ->setView('my-view') // The view that contains the web page content
            ->setTemplate('my-template'); // The template of the web page, assuming that my-template is located in the template folder.
    }
    // ...
}
```

### Template Location

Templates are stored in the `templates` folder. When a controller renders the `my-template` template, it is actually referring to the `my-project/template/my-template.php` file.

### Template Variables

A common need for templates is to print the values stored in the templates passed from the controller. This allows to evolve your application code without having to change the template code.

```php
// src/MyModule/Controller/MyController.php
namespace MyModule;

use Zana\Controller;

class MyController extends Controller
{
    public function welcome()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Welcome" // Document title to display in the browser tab
                'firstName' => $firstName // Variable $firstName passed to the view
            ])
            ->setView('my-view') // The view that contains the web page content
            ->setTemplate('my-template'); // The template of the web page, assuming that my-template is located in the template folder.
    }
    // ...
}
```

### Rendering Templates

Since the controller extends from the `Zana\Controller\Controller`, you can use the page object that is responsible for generating the web page from the view and the template.

```php
// src/MyModule/Controller/MyController.php
namespace MyModule;

use Zana\Controller;

class MyController extends Controller
{
    public function welcome()
    {
        // Return the page object to generate the web page
        return $this->page
            ->addVars([
                'dTitle' => "Welcome"
                'firstName' => $firstName
            ])
            ->setView('my-view')
            ->setTemplate('my-template');
    }
    // ...
}
```

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
// src/MyModule/Controller/MyController.php
namespace Main\Controller;

use Zana\Controller;

class MyController extends Controller
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
    <!-- src/MyModule/view/hello-world.php -->
    
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


## Databases and Data Access Objects

Zana provides a data access object (DAO) layer to use databases in your application. The DAO support databases like MySQL, PostgreSQL and SQLite.

### Configuring the Database

...

### Creating an Entity Class

...

### Persisting Objects to the Database

...

### Fetching Objects from the Database

...

### Updating an Object

...

### Deleting an Object

...

### Querying for Objects

### Querying with the DAO

...

### Querying with the Query Builder

Zana also provides a Query Builder, an object-oriented way to write queries. It is recommended to use this when queries are built dynamically.

```php
// Create a DAO instance
$dao = new MySQLDAO(MyEntity::class);

// Create a QueryBuilder instance
$queryBuilder = $dao->queryBuilder();

// Build a SELECT query with named parameters
$queryBuilder->select()
     ->where('id', [':id_1', ':id_2', ':id_3'])
     ->where('age', ':age');

// Get the built query
$queryString = $queryBuilder->build();

// Prepare the conditions for execution
$conditions = [
    ':id_1' => 1,
    ':id_2' => 2,
    ':id_3' => 8,
    ':age' => 18
];

// Execute the query
try {
    $resultSet = $dao->executeQuery($queryString, $conditions);
    
    // Handle the results
    foreach ($resultSet->all() as $object) {
        // Assuming object's class has a method to get object details
        echo $object->getId() . "\n"; // Example of accessing object data
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

Build a `SELECT` with joins.

```php
// Create a DAO instance
$dao = new MySQLDAO(MyEntity::class);

// Create a QueryBuilder instance
$queryBuilder = $dao->queryBuilder();

// Build a SELECT query with joins
$queryBuilder->select(['users.id', 'users.name', 'profiles.bio'])
             ->join('profiles', 'users.id', '=', 'profiles.user_id')
             ->where('users.age', 18)
             ->orderBy('users.name');

// Get the built query
$queryString = $queryBuilder->build();

// Prepare the conditions for execution
$conditions = [
    ':age' => 18
];

// Execute the query using the DAO class
$resultSet = $dao->executeQuery($queryString, $conditions);

// Handle the results
foreach ($resultSet->all() as $object) {
    // Assuming object's class has a method to get object details
    echo $object->getId() . "\n"; // Example of accessing object data
}
```


## Creators

**Jefferson Mwanaut**

- <https://github.com/jeffersonmwanaut>
- <https://www.linkedin.com/in/jeffersonmwanaut>
