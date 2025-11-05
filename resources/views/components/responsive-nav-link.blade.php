@props(['active'])

@php
$classes = ($active ?? false)
            // Mantém a borda esquerda para mostrar o 'ativo', mas tira TODAS as cores
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium focus:outline-none transition duration-150 ease-in-out'
            
            // Tira TODAS as cores e hovers
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>