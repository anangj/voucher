<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'dashcode') }}</title>
        <x-favicon/>
        {{-- Scripts --}}
        @vite(['resources/css/app.scss', 'resources/js/custom/store.js'])
    </head>
    <body>

        <div class="loginwrapper">
            <div class="lg-inner-column">
                <div class="relative right-column">
                    <div class="flex flex-col h-full bg-white inner-content dark:bg-slate-800">
                        {{ $slot }}
                        <div class="text-center auth-footer">
                            {{ __('Copyright') }}
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            , <a href="#">{{ __('Ciputra Hospital') }}</a>
                            {{ __('Care For Your Health & Happiness') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @vite(['resources/js/app.js'])
    </body>
</html>
