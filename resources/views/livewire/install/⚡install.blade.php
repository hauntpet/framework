<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;
use Haunt\Framework\Enums\InstallSteps;
use Haunt\Framework\Livewire\Forms\InstallForm;
use Haunt\Framework\Livewire\Forms\Install as InstallForms;

new #[Layout('haunt-framework::layout')] class extends Component {
    public array $stepCurrent;
    public array $steps;

    public function mount()
    {
        $this->steps = collect(InstallSteps::toValues())->transform(fn ($v) => ['step' => $v, 'complete' => false])->toArray();
        $this->stepCurrent = $this->steps[0];
    }
};
?>

<div class="bg-white rounded-lg shadow p-4 w-md">
    <div class="flex gap-2 items-center justify-between">
        <div class="text-xl font-medium">
            Haunt Install
        </div>
        <div class="flex items-center gap-1">
            @foreach($steps as $s)
                <div class="h-2 w-2 rounded-full {{
                    $s['complete'] ? 'bg-green-500' :
                    ($s['step'] === $stepCurrent['step'] ? 'bg-blue-500' : 'bg-mauve-300')
                }}"></div>
            @endforeach
        </div>
    </div>
    @if($stepCurrent['step'] === 'database')
        <livewire:haunt-framework::install.database-form />
    @endif
</div>
