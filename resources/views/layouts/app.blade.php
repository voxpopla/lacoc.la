<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/site.css', 'resources/js/site.js'])
    @livewireStyles
    @livewireScriptConfig
  </head>
  <body {{ $attributes->class('antialiased bg-off-white') }}>
    @include('sections.alert')

    {{ $slot }}
  </body>
</html>
