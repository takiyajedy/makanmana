<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold h4">
            Menu - {{ $restaurant->name }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <p class="text-muted">{{ $restaurant->address }}</p>

        @if ($restaurant->menus->count())
            <ul class="list-group">
                @foreach ($restaurant->menus as $menu)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $menu->food_name }}
                        @if ($menu->price)
                            <span class="badge bg-success">
                                RM{{ number_format($menu->price, 2) }}
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-warning">Tiada menu direkodkan.</div>
        @endif

        <a href="{{ route('recommend.index') }}" class="btn btn-outline-secondary mt-3">
            ← Kembali
        </a>
    </div>
</x-app-layout>
