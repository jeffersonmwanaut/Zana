<p align="center">
    <img src="public/img/icon.png" alt="Zana logo" width="200" height="165">
</p>

<h3 align="center">Zana</h3>

# Zana 5

Turnkey PHP framework offering components that serve as a foundation and toolbox for your project.

## Table of contents

- [Software requirements](#software-requirements)
- [Quick start](#quick-start-up)
- [What's included](#whats-included)
- [Creators](#creators)

## Software requirements

PHP version 7 or newer to develop using Zana. Other requirements are enforced by composer. See the require section of the `composer.json` file for details.

## Quick start-up

### Installation

Download the compressed file, Unzip it directly to the root of your project and quickly [create your first page](#hello-world).

### Hello world

1. Open the Main module `./src/Main/Main.php` and add a new route for the Hello world example.
```php
class Main extends Module
{
    public function __construct()
    {
        // ...

        Router::get(
            '/hello-world', // The route or URI
            'Main\Controller\MainController#helloWorld', // The controller or callback that will process the URI and render the page
            'HELLO_WORLD' // The route name
        );
    }
}
```
2. Create the file `./src/Main/view/hello-world.php` that will be used as view by the controller to render the page.
```php
<h1>Hello world!</h1>
```
3. Open the Main controller `./src/Main/Controller/MainController.php` and add a new callback that will process the hello world URI and render the page.
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
            ->setView(ROOT . '/src/Main/view/hello-world.php') // The content of the hello-world web page
            ->setTemplate(ROOT . '/template/base.template.php');
    }
}
```
4. Point to `http://localhost/my-project/hello-world` in the browser of choice to see the hello world page. Now you can start building with Zana by creating your own modules, controllers, views and templates.

## What's included

Within the download you'll find the following directories and files.

<details>
  <summary>Download contents</summary>

  ```text
  zana/
  ├── config/
  │   ├── ABAC/
  │   │   └── policy.json
  │   ├── com.php
  │   ├── dev.php
  │   ├── mode.txt
  │   ├── modules.php
  │   └── prod.php
  ├── public/
  │   ├── css/
  │   │   └── app.css
  │   ├── img/
  │   │   ├── icon.png
  │   │   ├── zana-1.png
  │   │   └── zana-2.png
  │   ├── js/
  │   │   └── app.js
  │   └── app.php
  ├── src/
  │   ├── Doc/
  │   │   ├── Controller/
  │   │   │   └── DocController.php
  │   │   ├── view/
  │   │   │   ├── default.php
  │   │   │   ├── download.php
  │   │   │   ├── example.php
  │   │   │   └── structure.php
  │   │   └── Doc.php
  │   └── Main/
  │       ├── Controller/
  │       │   └── MainController.php
  │       ├── view/
  │       │   └── website-under-construction.php
  │       └── Main.php
  ├── template/
  │   ├── partial/
  │   │   ├── base-footer.inc.php
  │   │   └── base-header.inc.php
  │   ├── base.template.php
  │   └── zana.template.php
  ├── vendor/
  │   ├── zana/
  │   │   ├── ABAC/
  │   │   │   └── Policy.php
  │   │   ├── Config/
  │   │   │   ├── com.php
  │   │   │   ├── Config.php
  │   │   │   ├── dev.php
  │   │   │   ├── mode.txt
  │   │   │   ├── modules.php
  │   │   │   └── prod.php
  │   │   ├── Cookie/
  │   │   │   └── Cookie.php
  │   │   ├── Database/
  │   │   │   ├── Connection/
  │   │   │   │   ├── MySQLDB.php
  │   │   │   │   ├── PostgreSQLDB.php
  │   │   │   │   └── SQLiteDB.php
  │   │   │   ├── DAO/
  │   │   │   │   ├── MySQLDAP.php
  │   │   │   │   ├── PostgreDAO.php
  │   │   │   │   └── SQLiteAO.php
  │   │   │   ├── DbFactory.php
  │   │   │   └── DbType.php
  │   │   ├── Entity/
  │   │   │   ├── Entity.php
  │   │   │   └── JsonSerializableEntity.php
  │   │   ├── Http/
  │   │   │   ├── HttpException.php
  │   │   │   ├── HttpRequest.php
  │   │   │   ├── HttpResponse.php
  │   │   │   ├── Page.php
  │   │   │   └── PageFormat.php
  │   │   ├── Pattern/
  │   │   │   ├── DAO/
  │   │   │   │   ├── DAO.php
  │   │   │   │   └── IDAO.php
  │   │   │   ├── AbstractFactory.php
  │   │   │   └── Singleton.php
  │   │   ├── Router/
  │   │   │   ├── Route.php
  │   │   │   ├── Router.php
  │   │   │   └── RouterException.php
  │   │   ├── Session/
  │   │   │   ├── Session.php
  │   │   │   └── SessionInterface.php
  │   │   ├── Application.php
  │   │   ├── Controller.php
  │   │   ├── Exception.php
  │   │   └── Module.php
  │   └── autoload.php
  ├── .htaccess
  ├── composer.json
  ├── composer.lock
  └── README.md
  ```
</details>

## Creators

**Jefferson Mwanaut**

- <https://github.com/jeffersonmwanaut>
- <https://www.linkedin.com/in/jeffersonmwanaut>
