# Laravel CRUD Generator (Pilot)

![Laravel](https://img.shields.io/badge/Laravel-10%20%7C%2011%20%7C%2012-red)
![PHP](https://img.shields.io/badge/PHP-8%2B-blue)
![License](https://img.shields.io/badge/License-MIT-green)
![Composer](https://img.shields.io/badge/Composer-Package-orange)

Laravel CRUD Generator (Pilot) is a simple Laravel package that helps developers quickly configure an admin panel and generate a ready-to-use CRUD system.

This package installs via Composer and provides two Artisan commands that automatically configure an admin layout and generate a full CRUD module with AJAX functionality.

---

# Installation

First create a new Laravel project:

```bash
laravel new my-project
```
Move into the project directory:
```
cd my-project
```

Install the package via Composer:

```bash
composer require fida/laravel-crud-generator
```

---

# Available Commands

After installing the package, two Artisan commands will be available:

```
php artisan pilot:config
php artisan pilot:crud
```

---

# AdminLTE Configuration

Run the following command:

```bash
php artisan pilot:config
```

This command configures **AdminLTE v4** inside the Laravel project and creates the following files:

```
resources/views/layouts/app.blade.php
resources/views/layouts/partials/head.blade.php
resources/views/layouts/partials/header.blade.php
resources/views/layouts/partials/sidebar.blade.php
resources/views/layouts/partials/script.blade.php
resources/views/layouts/partials/footer.blade.php
public/assets/js/general.js
```

After these files are created, **AdminLTE v4 will be fully configured and ready to use**.

---

# CRUD Generator

Run the CRUD generation command:

php artisan pilot:crud ModelName --columns="table_column_name:column_type:validation"
Separate the columns using commas as delimiters.
```bash
php artisan pilot:crud Product --columns="name:string:required|max:10,price:decimal:required|min:0,qty:integer:nullable"
```

This command generates the following files:

```
app/Http/Controllers/ProductController.php
app/Http/Requests/ProductRequest.php
app/Models/Product.php

database/migrations/2026_03_15_183116_create_products_table.php

public/assets/js/product.js

resources/views/pages/product/index.blade.php
resources/views/pages/product/create.blade.php
resources/views/pages/product/edit.blade.php
resources/views/pages/product/table.blade.php
```

It will also automatically create a **products route** inside the `web.php` file.

Example:

```php
Route::resource('products', ProductController::class);
```

---

# Database Migration

Run the migration command:

```bash
php artisan migrate
```

This will create the products table in your database.

---

# Run Laravel Server

Start the Laravel development server:

```bash
php artisan serve
```

Now open your browser and go to:

```
http://localhost:8000/products
```

Your **CRUD system is now fully functional**.

---

# Customizing the CRUD

You can add more columns to your table.

### Step 1 — Update Migration

Add your required columns in the migration file.

### Step 2 — Update Create Form

Add inputs inside:

```
resources/views/pages/create.blade.php
```

### Step 3 — Frontend Validation

```
public/assets/js/product.js
```
Example validation:

```JavaScript
$('#create-product-form').validate({

    rules: {
        product: { required: true },
        // more fields
    },

    submitHandler: (form) => {

    }

});
```

### Step 4 — Backend Validation

Add validation rules inside:

```
app/Http/Requests/ProductRequest.php
```

Example validation:
```
public function rules(): array
{
    return [
        'product' => 'required|string|max:255',
    ];
}
```
---

# Editing Records

To support editing functionality, update:

```
resources/views/pages/product/edit.blade.php
```

Add inputs and validation rules according to your requirements.

---

# AJAX Based CRUD

The generated CRUD system works using **AJAX requests**, which provides:

* Faster interaction
* No page reload
* Better user experience

---

# Template Flexibility

Although this package configures **AdminLTE v4**, you can replace it with **any other admin template** based on your project needs.

---

# License

This package is open-source and available under the **MIT License**.

