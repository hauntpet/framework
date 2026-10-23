---
description: Sitewide settings for a Haunt application.
---

# haunt/settings

## Installation

### Via Composer

```shellscript
composer require haunt/settings
```

After the package is installed, you will need to install it for Haunt.

```bash
php artisan haunt:install-plugin haunt/settings
```

## Usage

{% hint style="info" %}
Requires an Auth User model with an `is_administrator` attribute.
{% endhint %}

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

#### Add to General Settings

You can add additional fields to the General Settings page using the `admin.settings.general` hook.

{% code overflow="wrap" %}
```php
use AlizHarb\LaravelHooks\Facades\Hook;
use Haunt\Settings\Filament\Pages\SettingsPage;

public function admin(Panel $panel): void
{
    Hook::addFilter('admin.settings.general', function (array $settings) {
        return array_merge($settings, [
            SettingsPage::getSettingField('minimum_age')
                ->label('Minimum Age')
                ->required(),
        ]);
    });
}
```
{% endcode %}

### Hooks

{% code overflow="wrap" %}
```php
use Haunt\Settings\SettingsConstants;

/** ACTIONS */
// in the mount method of every settings page, after the form is filled
SettingsConstants::SETTINGS_MOUNT
// handles saving the settings data
SettingsConstants::SETTINGS_SAVE

/** FILTERS */
// update the settings as they are filled as form data
SettingsConstants::SETTINGS_DATA
// update the schema used to generate the general settings form
SettingsConstants::SETTINGS_GENERAL_FORM
// add fields to the form on the general settings page
SettingsConstants::SETTINGS_GENERAL_FIELDS
```
{% endcode %}
