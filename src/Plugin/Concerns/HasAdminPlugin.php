<?php

namespace Haunt\Framework\Plugin\Concerns;

use Filament\Panel;

trait HasAdminPlugin
{
    abstract public function getId(): string;

    abstract public function admin(Panel $panel): void;

    public function register(Panel $panel): void
    {
        $this->admin($panel);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
