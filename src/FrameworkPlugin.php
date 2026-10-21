<?php

namespace Haunt\Framework;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Haunt\Framework\Models\Role;
use Haunt\Framework\Models\Setting;
use Haunt\Framework\Plugin\Concerns as PluginConcerns;
use Haunt\Framework\Plugin\HauntPlugin;

class FrameworkPlugin extends HauntPlugin implements Plugin
{
    use PluginConcerns\HasAdminPlugin;
    use PluginConcerns\HasMigrations;

    public function getId(): string
    {
        return 'haunt/framework';
    }

    public function admin(Panel $panel): void
    {
        // $panel
        //     ->resources([
        //         PostResource::class,
        //         CategoryResource::class,
        //     ])
        //     ->pages([
        //         Settings::class,
        //     ]);
    }

    public function install(): bool
    {
        return true;
    }

    public function uninstall(): bool
    {
        return true;
    }
}
