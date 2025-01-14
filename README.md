<h1 align="center" id="top">
    <picture>
        <source media="(prefers-color-scheme: dark)" srcset="./docs/media/artex-agency-logo-dark.png">
        <img width="54" alt="Artex Agency Logo" src="./docs/media/artex-agency-logo.png">
    </picture>    
    <br>
    <strong>
        DI-CONTAINER
        <br>
        <small><sup>artex software</sup></small>
    </strong>
</h1>

<p align="center"><strong>A PSR-11 compliant dependency injection and service container library for modern PHP applications.</strong></p>

<p align="center">
    <a href="https://packagist.org/packages/artex/di-container">
        <img src="https://img.shields.io/packagist/v/artex/di-container" alt="Latest Version">
    </a>
    <a href="https://packagist.org/packages/artex/di-container">
        <img src="https://img.shields.io/packagist/dt/artex/di-container" alt="Total Downloads">
    </a>
    <a href="LICENSE">
        <img src="https://img.shields.io/badge/license-Apache%202.0-blue" alt="License">
    </a>
</p>

---

Artex DI-Container is a lightweight, feature-rich library designed to simplify dependency management in PHP. Built with **high performance**, **flexibility**, and **extensibility** in mind, this library combines the power of dependency injection with a robust service container.

## Key Features
- **PSR-11 Compliant**: Adheres to the `ContainerInterface` standard for maximum interoperability.
- **Dependency Injection**: Autowire classes and resolve dependencies effortlessly.
- **Service Management**: Tagged services, lifecycle hooks, deferred loading, and more.
- **Flexibility**: Perfect for frameworks, microservices, or standalone applications.

---

## Installation
```sh
composer "require artex/di-container"
```


## Usage

### Basic Example

```php
use Artex\DIContainer\ServiceContainer;

$container = ServiceContainer::getInstance();

// Register a shared service
$container->singleton('logger', function () {
    return new Logger();
});

// Resolve the service
$logger = $container->get('logger');
```


### Tagged Services

```php
$container->set('handler1', new Handler1(), true, ['event']);
$container->set('handler2', new Handler2(), true, ['event']);

// Retrieve all services tagged with "event"
$handlers = $container->getByTag('event');

```

### Lifecycle Hooks

```php
$container->onRegister(function ($id, $concrete, $shared) {
    echo "Service {$id} registered.\n";
});

$container->onResolve(function ($id, $instance) {
    echo "Service {$id} resolved.\n";
});

$container->singleton('db', function () {
    return new DatabaseConnection();
});

```

&nbsp;
## Testing
Run tests using PHPUnit:
```bash
./vendor/bin/phpunit
```

&nbsp;



&nbsp;

## Credits

- [James Gober](https://github.com/jamesgober) - Developer
- [Artex Software](https://artexsoftware.com) - Sponsor


&nbsp;

## License
The Apache License 2.0. Please see [LICENSE](LICENSE) file for more information.