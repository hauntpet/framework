<?php

namespace Haunt\Framework\Models;

use Haunt\Framework\Extend\Model;

/**
 ***** Properties
 *
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string $plugin
 * @property string $description
 * @property string $version
 * @property array $dependencies
 * @property bool $installed
 * @property object $created_at
 * @property object $updated_at
 ***** Attributes
 ***** Relationships
 ***** Scopes
 ***** Methods
 */
class Plugin extends Model
{
    protected $table = 'plugins';

    protected $attributes = [
        'dependencies' => '[]',
        'installed' => false,
    ];

    protected $casts = [
        'dependencies' => 'array',
        'installed' => 'bool',
    ];

    public static function asClass(?string $trait = null)
    {
        return static::all()->transform(function ($p) use ($trait) {
            $instance = new ($p->plugin);

            return $trait ? (in_array($trait, class_uses($instance)) ? $instance : null) : $instance;
        })->filter();
    }
}
