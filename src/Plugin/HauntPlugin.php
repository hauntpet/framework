<?php

namespace Haunt\Framework\Plugin;

abstract class HauntPlugin
{
    abstract public function install(): bool;

    abstract public function uninstall(): bool;
}
