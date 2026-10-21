<?php

namespace Haunt\Framework\Plugin\Concerns;

trait HasSeeder
{
    abstract public function getSeeder(): string;
}
