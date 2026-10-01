<?php

namespace Haunt\Framework\Commands;

use Illuminate\Support\Collection;
use Haunt\Framework\Plugin\Manifest;
use Illuminate\Support\Facades\Schema;
use LumiteStudios\Common\Extend\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'haunt:install', description: 'Install haunt.')]
class Install extends Command
{
    private Collection $plugins;
    private array $installedPlugins;

    public function handle(Manifest $manifest)
    {
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

        if ($plugin->installed) {
            $this->components->task("Already Installed: {$plugin->key}");

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

        $this->components->task("Installing: {$plugin->key}", function () use ($plugin) {
            /**
             * instantiate the plugin
             */
            $class = new ($plugin->plugin);
            $traits = class_uses($class);

            /**
             * run through the plugin traits and actions
             * as needed.
             */
            if (in_array(PluginConcerns\HasMigrations::class, $traits)) {
                $migrationsPath = $class->getMigrationsPath();
                $path = "/vendor/{$plugin->key}{$migrationsPath}";
                $this->callSilent('migrate', [
                    '--path' => $path,
                ]);
            }

            if (in_array(PluginConcerns\HasSeeder::class, $traits)) {
                $seeder = $class->getSeeder();
                $this->callSilent('db:seed', [
                    '--class' => $seeder,
                ]);
            }

            if (! $plugin->wasRecentlyCreated) {
                $plugin->installed = true;
                $plugin->save();
            } else {
                plugin->update(['installed' => true]);
            }

            $this->installedPlugins[] = $plugin->key;
        });
    }
}
