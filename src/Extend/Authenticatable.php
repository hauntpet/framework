<?php
namespace Haunt\Framework\Extend;

use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\FilamentUser;
use LumiteStudios\Common\Model\Authenticatable as BaseAuthenticatable;

abstract class Authenticatable extends BaseAuthenticatable implements FilamentUser, HasName
{
    //
}
