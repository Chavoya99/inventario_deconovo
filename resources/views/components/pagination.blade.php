<div class="mb-4">
    @if ($paginator->hasPages())
        <nav class="flex justify-center mt-6">
            <ul class="inline-flex items-center gap-1">

                {{-- Botón Anterior --}}
                @if ($paginator->onFirstPage())
                    <li class="px-3 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        Anterior
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}"
                        class="px-3 py-2 bg-white border rounded-md hover:bg-gray-100">
                            Anterior
                        </a>
                    </li>
                @endif

                {{-- Números --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="px-3 py-2 text-gray-400">{{ $element }}</li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <span class="px-3 py-2 bg-teal-500 text-white rounded-md">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}"
                                    class="px-3 py-2 bg-white border rounded-md hover:bg-gray-100">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Botón Siguiente --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}"
                        class="px-3 py-2 bg-white border rounded-md hover:bg-gray-100">
                            Siguiente
                        </a>
                    </li>
                @else
                    <li class="px-3 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        Siguiente
                    </li>
                @endif
            </ul>
        </nav>
    @endif
</div>