---
description: The main framework for a Haunt application.
---

# haunt/framework

## Installation

```shellscript
composer require haunt/framework
```

After the package is installed, you will need to run the Haunt install command to setup the database.

```bash
php artisan haunt:install
```

## Admin

Haunt uses [Filament](https://filamentphp.com/) for it's admin dashboard. You can access the admin area at the `/!` page - this can be changed in the Haunt config file.
