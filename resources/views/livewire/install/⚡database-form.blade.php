<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Haunt\Framework\Facades\Haunt;
use Illuminate\Support\Facades\DB;
use Haunt\Framework\Livewire\Forms\Install\DatabaseForm;

new class extends Component {
    public bool $loading = false;
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

    public function submit()
    {
        // attempt to make a database connection
        try {
            Haunt::setGameDatabaseConnection([
                'host' => $this->database_host,
                'database' => $this->database_name,
                'username' => $this->database_username,
                'password' => $this->database_password,
            ]);
            DB::connection(Haunt::getGameDatabaseConnection())->getPdo();
            $this->dispatch('flash', message: 'Successfully made a database connection.', severity: 'success');
        } catch (\Exception $e) {
            $this->dispatch('flash', message: $e->getMessage(), severity: 'error');
        }

        sleep(1);
    }
};
?>

<form class="flex flex-col gap-2" wire:submit="submit">
    <livewire:haunt-framework::components.input-group label="Database Name">
        <input class="w-full" wire:model="database_name" type="text">
    </livewire:haunt-framework::components.input-group>
    <livewire:haunt-framework::components.input-group label="Database Username">
        <input class="w-full" wire:model="database_username" type="text">
    </livewire:haunt-framework::components.input-group>
    <livewire:haunt-framework::components.input-group label="Database Password">
        <input class="w-full" wire:model="database_password" type="text">
    </livewire:haunt-framework::components.input-group>
    <livewire:haunt-framework::components.input-group label="Database Host">
        <input class="w-full" wire:model="database_host" type="text">
    </livewire:haunt-framework::components.input-group>
    <div class="flex justify-between">
        <div>
            <span wire:loading>loading</span>
        </div>
        <livewire:haunt-framework::components.button />
    </div>
</form>
