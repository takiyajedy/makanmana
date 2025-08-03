<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold h4">
            {{ __('Recommendation') }}
        </h2>
        <a href="{{ route('recommendation.map') }}" class="btn btn-primary">
            {{ __('Map') }}
        </a>
    </x-slot>

    <div class="container py-4">
        @if ($restaurants->count())
            <div class="row g-4">
                @foreach ($restaurants as $restaurant)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $restaurant->name }}</h5>
                                <p class="card-text text-muted mb-2">
                                    {{ $restaurant->address ?? 'Alamat tidak tersedia' }}<br>
                                    <small class="text-secondary">Jenis: {{ $restaurant->cuisine_type ?? '-' }}</small>
                                </p>

                                @if ($restaurant->menus->count())
                                    <ul class="list-group list-group-flush flex-grow-1 mb-3">
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
                                    <p class="text-muted fst-italic">Tiada menu direkodkan.</p>
                                @endif

                                <a href="{{ route('restaurants.menu', $restaurant->id) }}" 
                                   class="btn btn-outline-primary btn-sm mt-auto">
                                    Lihat Menu
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning">
                Tiada restoran dalam senarai.
            </div>
        @endif
    </div>
</x-app-layout>
