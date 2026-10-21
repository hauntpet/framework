<?php

namespace Haunt\Framework\Services;

use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class HauntMigrationRepository extends DatabaseMigrationRepository
{
    public function createRepository()
    {
        $schema = $this->getConnection()->getSchemaBuilder();

        $schema->create($this->table, function ($table) {
            // The migrations table is responsible for keeping track of which of the
            // migrations have actually run for the application. We'll create the
            // table to hold the migration file's path as well as the batch ID.
            $table->increments('id');
            $table->string('migration');
            $table->string('batch');
        });
    }
}
