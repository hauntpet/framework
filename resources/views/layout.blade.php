<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @fluxAppearance
    </head>
    <body class="h-screen w-screen flex items-center justify-center bg-mauve-100">
        @yield('content')
        @livewireScripts
        @fluxScripts
    </body>
</html>
