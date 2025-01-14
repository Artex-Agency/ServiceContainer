<h1 align="center" id="top">
    <picture>
        <source media="(prefers-color-scheme: dark)" srcset="./docs/media/artex-agency-logo-dark.png">
        <img width="54" alt="Artex Agency Logo" src="./docs/media/artex-agency-logo.png">
    </picture>    
    <br>
    <strong>
        CHANGELOG<br><sup>DI-CONTAINER</sup>
    </strong>
</h1>

All notable changes to this project will be documented here. This changelog follows the principles of [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), ensuring clarity and consistency. Versioning adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

&nbsp;

## [Unreleased]


<!--==============================--->
<br><hr><br> <!--# BEGIN CHANGELOG
==================================-->

&nbsp;

## [1.0.0-RC.9] - 2025-01-14

### Added
- **Middleware Support**: Services now support pre- and post-instantiation hooks for advanced lifecycle management.
- **Contextual Bindings**: Define service implementations for specific use cases or contexts.
- **Factory Support**: Services can now be instantiated dynamically via factory callbacks.
- **Deferred Services**: Services can be registered lazily and instantiated only when accessed.
- **Scoped Services**: Manage service lifetimes for specific lifecycles, such as requests or sessions.
- **Tagged Services**: Services can now be tagged and retrieved as groups, enabling batch operations.
- **Clear and Remove**: Added functionality to clear all services or remove specific services.

### Changed
- Refactored the `get` method to include deferred service resolution and middleware application.
- Improved `has` method to check for deferred services alongside registered services.
- Middleware now applies to factory-created services for consistency.

### Fixed
- Addressed minor inconsistencies with PHPDoc annotations across the codebase.
- Resolved potential issues with circular dependencies in the `DependencyResolver`.


&nbsp;

## [1.0.0-RC.3] - 2024-01-05
### Added
- Initial implementation of **PSR-11 compliant** `ServiceContainer`.

&nbsp;


<!--==============================--->
<br><hr><br> <!--# BEGIN CHANGELOG
==================================-->

<!-- #########  PRERELEASE
---------------------------------------------------------------------->
[1.0.0-RC.3]:   https://github.com/artex-agency/di-container/compare/v1.0.0-RC.#...v1.0.0-RC6
[1.0.0-RC.9]:   https://github.com/artex-agency/di-container/compare/v1.0.0-RC.9...v1.0.0-RC3

<!--##########  STABLE
---------------------------------------------------------------------->
[1.0.0-RC.18]:  https://github.com/artex-agency/di-container/compare/v1.0.0-RC.18`...v1.0.0-RC9

<!-- ########  UNRELEASED [ temp container ]
---------------------------------------------------------------------->
[unreleased]:  https://github.com/artex-agency/di-container/compare/v1.0.0...HEAD