<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-t">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

         <!-- Font-Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Estilos do Livewire -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen  bg-center bg-no-repeat bg-fixed bg-blend-overlay"
        style="background-image: url('{{ asset('Fundo-Novo.png') }}');"
>
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-[#4989C1] text-white shadow"> <!-- COR FUNDO TEXTO "MAPA CENTRAL"-->
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @if(isset($slot) && trim($slot) !== '')
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>

        <!-- TOAST UNIVERSAL CORRIGIDO -->
        @php
            // Verifica se há mensagens de sessão (para redirects)
            $sessionMessage = null;
            $sessionType = 'success';

            if (session()->has('success')) {
                $sessionMessage = session('success');
                $sessionType = 'success';
            } elseif (session()->has('error')) {
                $sessionMessage = session('error');
                $sessionType = 'error';
            } elseif (session()->has('info')) {
                $sessionMessage = session('info');
                $sessionType = 'info';
            } elseif (session()->has('warning')) {
                $sessionMessage = session('warning');
                $sessionType = 'warning';
            }
        @endphp

        <div 
            x-data="{ 
                show: false, 
                message: '', 
                type: 'success',
                init() {
                    // CORREÇÃO: Inicializa com mensagem de sessão se existir
                    @if($sessionMessage)
                        this.showToast('{{ addslashes($sessionMessage) }}', '{{ $sessionType }}');
                    @endif
                },
                showToast(msg, toastType = 'success') {
                    this.message = msg;
                    this.type = toastType;
                    this.show = true;
                    setTimeout(() => this.show = false, 5000);
                }
            }"
            x-init="init()"
            @show-toast.window="showToast($event.detail.message, $event.detail.type)"
            class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50"
        >
            <!-- Toast -->
            <div 
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
                x-transition:leave-end="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
                class="max-w-sm w-full rounded-md px-4 py-3 text-white shadow-lg pointer-events-auto flex items-start"
                :class="{ 
                    'bg-green-500': type === 'success', 
                    'bg-red-600': type === 'error', 
                    'bg-blue-500': type === 'info',
                    'bg-yellow-500': type === 'warning'
                }"
                style="display: none;"
            >
                <!-- Ícone baseado no tipo -->
                <template x-if="type === 'success'">
                    <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                </template>
                <template x-if="type === 'error'">
                    <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                </template>
                <template x-if="type === 'info'">
                    <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                </template>
                <template x-if="type === 'warning'">
                    <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                </template>
                
                <span x-text="message" class="flex-1"></span>
                
                <!-- Botão fechar -->
                <button @click="show = false" class="ml-2 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        @livewireScripts
    </body>
</html>