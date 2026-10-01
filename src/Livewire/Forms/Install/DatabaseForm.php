<?php

namespace Haunt\Framework\Livewire\Forms\Install;

use Livewire\Attributes\Validate;
use Livewire\Form;

class DatabaseForm extends Form
{
    public $database_name = '';
    public $database_username = '';
    public $database_password = '';
    public $database_host = '';
}
