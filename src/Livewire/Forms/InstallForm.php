<?php

namespace Haunt\Framework\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class InstallForm extends Form
{
    #[Validate('required|min:5')]
    public $database_name = '';
    public $database_username = '';
    public $database_password = '';
    public $database_host = '';
}
