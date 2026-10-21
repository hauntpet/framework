<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;
use Haunt\Framework\Enums\InstallSteps;
use Haunt\Framework\Livewire\Forms\InstallForm;
use Haunt\Framework\Livewire\Forms\Install as InstallForms;

new class extends Component {
    public int $delay;
    public string $message;
    public bool $show = false;
    public string $severity;

    #[On('flash')]
    public function flash(string $message = 'message', string $severity = 'info', int $delay = 2)
    {
        $this->delay = $delay;
        $this->message = $message;
        $this->severity = $severity;
        $this->show = true;
        $this->dispatch('flashed')->to(self: true);
    }

    #[On('flashed')]
    public function flashed()
    {
        sleep($this->delay);
        $this->show = false;
    }
};
?>

<div class="fixed bottom-2 right-2">
    <div class="
        px-4 py-2 rounded-lg text-sm transition-all
        {{ $show ? 'opacity-100' : 'opacity-0' }}
        {{ $severity === 'error' ? 'bg-red-500/80 text-white' : '' }}
        {{ $severity === 'info' ? 'bg-blue-500/80 text-white' : '' }}
        {{ $severity === 'success' ? 'bg-green-500/80 text-white' : '' }}
    ">
        {{ $message }}
    </div>
</div>
