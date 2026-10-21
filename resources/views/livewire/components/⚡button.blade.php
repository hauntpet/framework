<?php

use Livewire\Component;

new class extends Component {
    public bool $loading = false;
    public string $label = 'Submit';
    public string $severity = 'success';
};
?>

<div>
    <button
        class="
            rounded-lg text-sm inline-block font-bold px-3 py-1.5 tracking-wide opacity-90 hover:opacity-100
            {{ $loading ? 'opacity-25!' : '' }}
            {{ $severity === 'success' ? 'bg-green-500 text-green-50 cursor-pointer' : '' }}
        "
    >{{ $label }}</button>
</div>
