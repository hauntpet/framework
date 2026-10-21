<?php

namespace Haunt\Framework\Commands;

use Haunt\Framework\Extend\Command;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

#[Signature('haunt:migrate-install {--database= : The database connection to use}')]
#[Description('Setup the haunt migrations table.')]
class MigrateInstallCommand extends Command
{
    protected $repository;

    public function __construct(MigrationRepositoryInterface $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function handle()
    {
        $this->repository->setSource($this->input->getOption('database'));

        if (! $this->repository->repositoryExists()) {
            $this->repository->createRepository();
        }

        $this->components->info('Migration table created successfully.');
    }
}
