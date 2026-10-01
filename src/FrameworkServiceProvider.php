<?php
namespace Haunt\Framework;

use Livewire\Livewire;
use Haunt\Framework\Commands;
use Haunt\Framework\Services\Haunt;
use Haunt\Framework\Plugin\Manifest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Haunt\Framework\Http\Middleware\IsInstalledMiddleware;

class FrameworkServiceProvider extends ServiceProvider
{
    protected string $root = __DIR__.'/..';

    protected $commands = [
        Commands\Install::class,
    ];

    public function boot(): void
    {
        $this->bootCommands()
            ->bootLivewire()
            ->bootLoad()
            ->bootManifest()
            ->bootMiddleware();
    }

    public function register(): void
    {
        $this->registerHaunt();
    }

    protected function bootCommands(): static
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }

        return $this;
    }

    protected function bootLivewire(): static
    {
        Livewire::addNamespace(
            namespace: 'haunt-framework',
            viewPath: "{$this->root}/resources/views/livewire",
        );

        return $this;
    }

    protected function bootLoad(): static
    {
        $this->loadRoutesFrom("{$this->root}/routes/web.php");
        $this->loadViewsFrom("{$this->root}/resources/views", 'haunt-framework');

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

    protected function registerHaunt(): static
    {
        $this->app->bind('haunt', function () {
            $haunt = new Haunt();

            return $haunt;
        });

        return $this;
    }
}
