<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Haunt\Framework\Livewire\Forms\Install\DatabaseForm;

new class extends Component {
    public $database_name = '';
    public $database_username = '';
    public $database_password = '';
    public $database_host = '';

    public function mount()
    {
        $this->database_name = config('database.connections.mysql.database');
        $this->database_username = config('database.connections.mysql.username');
        $this->database_password = config('database.connections.mysql.password');
        $this->database_host = config('database.connections.mysql.host');
    }

    public function save()
    {
        dd($this->form);
        // Save logic here...
    }
};
?>

<div class="grid grid-cols-2">
    <div class="uppercase text-sm font-medium">
        key
    </div>
</div>
