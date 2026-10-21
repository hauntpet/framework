<?php

namespace Haunt\Framework\Plugin;

use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Manifest extends PackageManifest
{
    public function plugin(string $plugin)
    {
        return $this->plugins()
            ->get($plugin);
    }

    public function plugins()
    {
        return collect($this->getManifest());
    }

    public function build()
    {
        $packages = [];

        if ($this->files->exists($path = $this->vendorPath.'/composer/installed.json')) {
            $installed = json_decode($this->files->get($path), true);
            $packages = $installed['packages'] ?? $installed;
        }

        $output = collect($packages)
            // ->filter(fn ($p) => Arr::has($p, 'extra.haunt') || $p['name'] === 'haunt/framework')
            ->filter(fn ($p) => Arr::has($p, 'extra.haunt'))
            ->keyBy('name');

        $output = $output->map(fn ($p) => $this->formatPackage($p, $output))
            ->all();

        $this->write($output);

        $this->getManifest();
    }

    protected function formatPackage(array $package, Collection $packages): array
    {
        $dependencies = collect($package['require'])
            ->filter(fn ($v, $r) => $packages->has($r))
            ->keys()
            ->all();

        return [
            'key' => $package['name'],
            'name' => $package['extra']['haunt']['name'],
            'plugin' => $package['extra']['haunt']['plugin'],
            'description' => $package['extra']['haunt']['description'] ?? $package['description'],
            'version' => $package['version'],
            'dependencies' => $dependencies,
        ];
    }
}
