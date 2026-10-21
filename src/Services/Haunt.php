<?php

namespace Haunt\Framework\Services;

use Illuminate\Support\Facades\Schema;

class Haunt
{
    public static string $migrationsTable = 'haunt_migrations';

    public string $gameConnection = 'haunt-game';

    /**
     * Check if Haunt has already been installed.
     */
    public function isInstalled(): bool
    {
        return true;

        return false;

        return Schema::hasTable(self::pluginsTableName());
    }

    /**
     * The name of the plugins table.
     */
    public function pluginsTableName(): string
    {
        return 'plugins';
    }
}
