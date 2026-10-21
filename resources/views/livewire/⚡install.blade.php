<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;
use Haunt\Framework\Enums\InstallSteps;
use Haunt\Framework\Livewire\Forms\InstallForm;
use Haunt\Framework\Livewire\Forms\Install as InstallForms;

new #[Layout('haunt::layout')] class extends Component {
    public array $form = [
        'token' => '',
    ];

    public function mount()
    {
        $this->form['token'] = config('haunt-id.token');
    }
};
?>

<div class="bg-white rounded-lg shadow p-4 w-md">
    <div class="text-xl font-medium">Install Haunt</div>
    <x-haunt::input-group label="Haunt API Token">
        <x-haunt::form.input class="w-full" type="text" wire:model="form.token" readonly />
    </x-haunt::input-group>
    <div class="text-sm italic text-mauve-500">
        test
    </div>
</div>
