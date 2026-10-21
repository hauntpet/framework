<?php

namespace Haunt\Framework\Commands;

use Haunt\Framework\Models\Plugin;
use Haunt\Framework\Extend\Command;
use Haunt\Framework\Plugin\Manifest;
use Illuminate\Database\QueryException;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;
use Haunt\Framework\Plugin\Concerns as PluginConcerns;

#[Signature('haunt:install-plugin {plugin} {--force}')]
#[Description('Install a haunt plugin.')]
class InstallPluginCommand extends Command
{
    public function handle(Manifest $manifest)
    {
        $key = $this->argument('plugin');
        $force = $this->option('force');

        try {
            $plugin = Plugin::where('key', $key)->firstOrFail();
        } catch (\Exception $exception) {
            $manifest->build();
            $plugin = Plugin::make($manifest->plugin($key));
        }

        if ($plugin->installed && ! $force) {
            $this->components->task("Already Installed: {$plugin->key}");

            return;
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
                $this->callSilent('haunt:migrate', [
                    '--path' => $path,
                    '--plugin' => $plugin->key,
                ]);
            }

            if (in_array(PluginConcerns\HasSeeder::class, $traits)) {
                $seeder = $class->getSeeder();
                $this->callSilent('db:seed', [
                    '--class' => $seeder,
                ]);
            }

            $state = $this->validate(
                fn () => $class->install(),
                fn () => false,
            );

            if (! $state) {
                return 2; /** FAILED */
            }

            if (! $plugin->wasRecentlyCreated) {
                $plugin->installed = true;
                $plugin->save();
            } else {
                $plugin->update(['installed' => true]);
            }
        });

    }
}
