---
description: Sitewide settings for a Haunt application.
---

# haunt/settings

## Installation

```shellscript
composer require haunt/settings
```

After the package is installed, you will need to install it for Haunt.

```bash
php artisan haunt:install-plugin haunt/settings
```

## Usage

### Creating New Settings

Within the \`install\` method of a Haunt Plugin, you can create new application settings:

```php
use Haunt\Settings\Models\Setting;

Setting::add(
    'state_game', /** key */
    'alpha', /** default value */
    ['required', 'string'] /** validation rules */
);
```

### Adding to Admin Area

You can create a new page to allow admins to update settings.

```php
use Filament\Forms\Components;
use Filament\Schemas\Schema;
use Haunt\Settings\Filament\Pages\SettingsPage;

class General extends SettingsPage
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getSettingField('state_game')
                    ->label('Game State')
                    ->required(),
            ]);
    }
}
```

Make sure to register it in the Haunt Plugin class.

```php
use Filament\Contracts\Plugin;
use Filament\Panel;
use Haunt\Framework\Plugin\HauntPlugin;
use Haunt\Framework\Plugin\Concerns\HasAdminPlugin;

class NewPlugin extends HauntPlugin implements Plugin
{
    use HasAdminPlugin;

    public function admin(Panel $panel): void
    {
        $panel
            ->pages([
                General::class,
            ]);
    }
}
```

