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
* Install PHP 7 or higher;
* [Install composer](https://getcomposer.org/download/), which is used to install PHP packages.

## Quick start-up

### Installation

Download the compressed file, Unzip it directly to the root of your project and quickly [create your first page](#hello-world).

### Hello world

Creating a new page is a two-step process:
1. Create a route: A route is the URL (e.g. /hello-world) to your page and points to a controller.
2. Create a controller: A controller is the PHP function you write that builds the page. You take the incoming request information and use it to create a Zana page object, which can hold HTML, TEXT, JSON or XML content.

#### Create a route

Open the Main module `./src/Main/Main.php` and add a new route `/hello-world` for the Hello world example.

```php
class Main extends Module
{
    public function __construct()
    {
        // ...

        Router::get(
            '/hello-world', // The route or URI
            'Main\Controller\MainController#helloWorld', // The controller function that will build the page
            'HELLO_WORLD' // The route name
        );
    }
}
```

#### Create a controller

1. Open the Main controller `./src/Main/Controller/MainController.php` and add a new function `helloWorld()` that will build the page.
```php
class MainController extends Controller
{
    // ...

    public function helloWorld()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Hello world" // Document title to display in the browser tab
            ])
            ->setView('hello-world'); // The view that contains the hello-world web page content
    }
}
```

2. Create the view `./src/Main/view/hello-world.php` that will be used by the controller to render the page.
```php
<h1>Hello world!</h1>
```

That's it! Now open your browser and navigate to `http://localhost/my-project/hello-world`. If everything is working, you will see the hello world page.

## Project structure
`config/`
<p>Contains configuration.</p>

`public/`
<p>This is the document root for your project: you put any publicly accessible files here.</p>

`template/`
<p>All your PHP code goes here.</p>

`src/`
<p>All your templates go here.</p>

`vendor/`
<p>Zana code and third-party libraries live here! <br>Third-party libraries are downloaded via [Composer](https://getcomposer.org/download/)</p>

## What's next

Bravo! You've learned a whole new way of building beautiful and functional applications.
<br>To finish mastering the fundamentals, read these articles:
* [Routing](#routing)
* [Controller](#controller)
* [Templates](#templates)
* [Front-end Tools: Bootstrap](#front-end-tools-bootstrap)
* [Configuring Zana](#configuring-zana)

## Routing

### Creating Routes

...

### Route Parameters

...

### Route Aliasing

...

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
