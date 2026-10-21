<?php

namespace Haunt\Framework\Commands;

use Haunt\Framework\Extend\Command;
use Haunt\Framework\Models\Plugin;
use Haunt\Framework\Plugin\Manifest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

#[Signature('haunt:install {--force}')]
#[Description('Install haunt.')]
class InstallCommand extends Command
{
    private Collection $plugins;

    private array $installedPlugins;

    public function handle(Manifest $manifest)
    {
        $this->installFramework();

        $manifest->build();

        /**
         * get the plugins from the manifest and
         * a plugin model for them.
         */
        $this->plugins = $manifest->plugins()
            ->map(fn ($plugin) => $this->getPluginModel($plugin));
        $this->installedPlugins = [];

        /**
         * install each plugin
         */
        $this->plugins->each(fn ($plugin) => $this->installPlugin($plugin));
    }

    private function installFramework()
    {
        $path = "/vendor/haunt/framework/database/migrations";
        $this->callSilent('haunt:migrate', [
            '--path' => $path,
            '--plugin' => 'haunt/framework',
        ]);
    }

    private function getPluginModel(array $plugin): Plugin
    {
        if (! Schema::hasTable('plugins')) {
            $model = Plugin::make($plugin);
        } else {
            $model = Plugin::where('key', $plugin['key'])->first();

            if (! $model) {
                $model = Plugin::make($plugin);
            }
        }

        return $model;
    }

    private function installPlugin(Plugin $plugin)
    {
        if (in_array($plugin->key, $this->installedPlugins)) {
            return;
        }

        /**
         * first: check if the plugin has any dependencies
         * if so, install them first
         * this is recursive... i think
         */
        if (count($plugin->dependencies) > 0) {
            collect($plugin->dependencies)->each(fn ($dep) => $this->installPlugin($this->plugins[$dep]));
        }

        $this->call('haunt:install-plugin', [
            'plugin' => $plugin->key,
            '--force' => $this->option('force'),
        ]);

        $this->installedPlugins[] = $plugin->key;
    }
}
