# Hooks

Haunt makes use of the [Laravel Hooks](https://github.com/AlizHarb/laravel-hooks) package.

## Model

You can dynamically add a `relation`, `scope`, `accessor`, `attribute`, or `method` to another plugins models.

{% code overflow="wrap" %}
```php
use AlizHarb\LaravelHooks\Facades\Hook;

Hook::model(User::class)
    ->relation('items', fn ($user) => $user->hasMany(Item::class));
```
{% endcode %}
