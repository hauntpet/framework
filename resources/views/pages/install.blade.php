@extends('haunt::layout')

@section('content')
<flux:card class="w-md max-w-full">
<!-- <div class="bg-white rounded-lg shadow p-4 w-md"> -->
    <div class="text-xl font-medium">Install Haunt</div>
    <flux:field>
        <flux:label>Haunt API Token</flux:label>
        <flux:input :value="$token" readonly variant="filled" />
        <flux:error name="username" />
    </flux:field>
    <!-- <x-haunt::input-group label="Haunt API Token">
        <x-haunt::form.input class="w-full" type="text" :value="$token" readonly />
    </x-haunt::input-group> -->
    <div class="text-sm italic text-mauve-500">
        test
    </div>
<!-- </div> -->
</flux:card>
@endsection
