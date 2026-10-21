<?php

namespace Haunt\Framework\Providers;

use Haunt\Framework\Commands;
use Haunt\Framework\Models\Plugin;
use Haunt\Framework\Services\Haunt;
use Haunt\Framework\Plugin\Manifest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Haunt\Framework\Services\HauntMigrator;
use Illuminate\Contracts\Events\Dispatcher;
use Haunt\Framework\Plugin\Concerns\HasCommands;
use Haunt\Framework\Services\HauntMigrationRepository;
use Haunt\Framework\Http\Middleware\IsInstalledMiddleware;

class FrameworkServiceProvider extends ServiceProvider
{
    protected string $root = __DIR__.'/../..';

    protected $commands = [
        Commands\InstallCommand::class,
        Commands\InstallPluginCommand::class,
        Commands\MigrateCommand::class,
        Commands\MigrateInstallCommand::class,
    ];

    public function boot(): void
    {
        $this->bootCommands()
            ->bootLoad()
            ->bootManifest()
            ->bootMiddleware();
    }

    public function register(): void
    {
        $this->registerConfig()
            ->registerHaunt()
            ->registerMigrationRepository()
            ->registerMigrator()
            ->registerMigrateInstallCommand()
            ->registerMigrateCommand();
    }

    protected function bootCommands(): static
    {
        if ($this->app->runningInConsole()) {
            $pluginCommands = [];
            if(Schema::hasTable('plugins')) {
                $pluginCommands = Plugin::asClass(HasCommands::class)->flatMap(fn ($p) => $p->commands())->toArray();
            }
            $this->commands(array_merge($this->commands, $pluginCommands));
        }

        return $this;
    }

    protected function bootLoad(): static
    {
        $this->loadViewsFrom("{$this->root}/resources/views", 'haunt');

        return $this;
    }

    protected function bootManifest(): static
    {
        $cachePath = $this->app->bootstrapPath().'/cache/plugins.php';

        $this->app->instance(Manifest::class, new Manifest(
            new Filesystem,
            $this->app->basePath(),
            $cachePath
        ));

        return $this;
    }

    protected function bootMiddleware(): static
    {
        $this->app['router']->pushMiddlewareToGroup('web', IsInstalledMiddleware::class);

        return $this;
    }

    protected function registerConfig(): static
    {
        $this->mergeConfigFrom("{$this->root}/config/haunt.php", 'haunt');
        $this->publishes(["{$this->root}/config/haunt.php" => config_path('haunt.php')]);

        return $this;
    }

    protected function registerHaunt(): static
    {
        $this->app->bind('haunt', function () {
            $haunt = new Haunt;

            return $haunt;
        });

        return $this;
    }

    protected function registerMigrateCommand(): static
    {
        $this->app->singleton(Commands\MigrateCommand::class, function ($app) {
            return new Commands\MigrateCommand($app['haunt.migrator'], $app[Dispatcher::class]);
        });

        return $this;
    }

    protected function registerMigrateInstallCommand(): static
    {
        $this->app->singleton(Commands\MigrateInstallCommand::class, function ($app) {
            return new Commands\MigrateInstallCommand($app['haunt.migration.repository']);
        });

        return $this;
    }

    protected function registerMigrationRepository(): static
    {
        $this->app->singleton('haunt.migration.repository', function ($app) {
            return new HauntMigrationRepository($app['db'], Haunt::$migrationsTable);
        });

        return $this;
    }

    protected function registerMigrator(): static
    {
        $this->app->singleton('haunt.migrator', function ($app) {
            $repository = $app['haunt.migration.repository'];

            return new HauntMigrator($repository, $app['db'], $app['files'], $app['events']);
        });

        $this->app->bind(HauntMigrator::class, fn ($app) => $app['haunt.migrator']);

        return $this;
    }
}
