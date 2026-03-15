# Laravel CRUD Generator

Laravel CRUD Generator is a simple package that helps you quickly generate a complete CRUD system in your Laravel project with AdminLTE v4 UI.

The package automatically creates all necessary backend and frontend files including controllers, models, migrations, requests, views, JavaScript, and routes.

---

## Create New Laravel Project

If you are starting a new project, run:

```bash
laravel new my-project
cd my-project
```

---

## Installation

Install the package using Composer:

```bash
composer require fida/laravel-crud-generator
```

---

## Setup Admin Template

After installing the package, run the following command:

```bash
php artisan pilot:config
```

This command will install and configure the **AdminLTE v4** template inside your Laravel project.

---

## Generate CRUD

To generate a complete CRUD module, run:

```bash
php artisan pilot:crud ModelName
```

### Example

```bash
php artisan pilot:crud Product
```

---

## Run The Project

Start the Laravel development server:

```bash
php artisan serve
```

Then open your browser and navigate to:

```
http://localhost:8000
```

---

## What This Command Generates

The `pilot:crud` command will automatically generate:

- Model
- Migration
- Controller
- Form Request (Validation)
- Views
  - Index Page
  - Create Form (Modal)
  - Edit Form (Modal)
  - Table View
- JavaScript File
  - Form validation
  - AJAX requests
  - Table rendering
- Routes
- Sidebar menu item

---

## Features

- Full CRUD functionality
- AJAX based forms
- Dynamic table rendering
- Validation using Laravel Form Requests
- AdminLTE v4 UI integration
- Automatic sidebar menu generation
- Clean JavaScript structure

---

## Workflow

1. Create a new Laravel project:

```bash
laravel new my-project
cd my-project
```

2. Install the CRUD generator package:

```bash
composer require fida/laravel-crud-generator
```

3. Configure the admin template:

```bash
php artisan pilot:config
```

4. Generate CRUD module:

```bash
php artisan pilot:crud ModelName
```

5. Start the server and view your project:

```bash
php artisan serve
```

Open your browser at:

```
http://localhost:8000
```

Your CRUD module will now be ready to use.

---

## Requirements

- PHP 8+
- Laravel 10+

---

## License

This package is open-source and available under the MIT License.
