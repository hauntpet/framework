<?php

namespace Haunt\Framework\Plugin\Concerns;

trait HasMigrations
{
    public function getMigrationsPath(): string
    {
        return '/database/migrations';
    }
}
