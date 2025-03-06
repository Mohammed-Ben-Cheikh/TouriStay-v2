<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Sidebar avec filtres -->
                            <div class="w-full md:w-80 flex-shrink-0 ">
                                <div class="bg-white p-4 rounded-lg shadow sticky top-6 h-[80vh] overflow-y-auto">
                                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h2>
                                    <form action="{{ route('hébergements.index') }}" method="GET" class="space-y-4">
                                        <!-- Type de logement -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Type de logement</label>
                                            <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Tous les types</option>
                                                @foreach([
                                                    'apartment' => 'Appartement',
                                                    'house' => 'Maison',
                                                    'studio' => 'Studio'
                                                ] as $value => $label)
                                                    <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Pays -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                                            <select name="country" id="country-select" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Tous les pays</option>
                                                @foreach($citiesByCountry as $country => $cities)
                                                    <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                                        {{ $country }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Ville -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                            <select name="city" id="city-select" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Toutes les villes</option>
                                            </select>
                                        </div>
                                        <!-- Prix -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix par nuit</label>
                                            <select name="price" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Tous les prix</option>
                                                @foreach([
                                                    '0-50' => '0€ - 50€',
                                                    '51-100' => '51€ - 100€',
                                                    '101-200' => '101€ - 200€',
                                                    '201+' => '201€ et plus'
                                                ] as $value => $label)
                                                    <option value="{{ $value }}" {{ request('price') == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
            
                                        <!-- Chambres -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Chambres</label>
                                            <select name="bedrooms" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Toutes les tailles</option>
                                                @foreach([1, 2, 3, '4+'] as $bedroom)
                                                    <option value="{{ $bedroom }}" {{ request('bedrooms') == $bedroom ? 'selected' : '' }}>
                                                        {{ $bedroom }} {{ $bedroom == '4+' ? 'ou plus' : ($bedroom > 1 ? 'chambres' : 'chambre') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Équipements -->
                                        <div class="border-t pt-4">
                                            <h3 class="text-sm font-medium text-gray-900 mb-2">Équipements</h3>
                                            <div class="space-y-2">
                                                @foreach([
                                                    'wifi' => 'Wi-Fi',
                                                    'parking' => 'Parking',
                                                    'kitchen' => 'Cuisine',
                                                    'tv' => 'TV',
                                                    'aircon' => 'Climatisation',
                                                    'pool' => 'Piscine',
                                                    'washing_machine' => 'Lave-linge',
                                                    'elevator' => 'Ascenseur'
                                                ] as $value => $label)
                                                    <label class="flex items-center">
                                                        <input type="checkbox" 
                                                               name="equipments[]" 
                                                               value="{{ $value }}" 
                                                               {{ in_array($value, (array)request('equipments')) ? 'checked' : '' }}
                                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
            
                                        <div class="pt-4">
                                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                                Appliquer les filtres
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
            
                            <!-- Liste des appartements -->
                            <div class="flex-grow">
                                <!-- Search Results Header -->
                                <section class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 max-w-6xl mx-auto mb-8">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-gray-200 pb-4 mb-6">
                                        <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Résultats de la recherche</h2>
                                        <div class="flex items-center gap-4 mt-4 sm:mt-0">
                                            <span class="text-sm font-medium text-gray-600 bg-gray-50 px-3 py-1 rounded-full shadow-sm">
                                                {{ $apartments->total() }} résultats trouvés
                                            </span>
                                            <span class="text-xs text-gray-500 italic hidden sm:block">
                                                Mise à jour : {{ now()->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                                        <div class="flex items-center gap-4">
                                            <label for="numPagination" class="text-sm font-medium text-gray-700 whitespace-nowrap">Résultats par page :</label>
                                            <form action="{{ route('hébergements.index') }}" method="GET" class="relative">
                                                @csrf
                                                <select id="numPagination" name="numPagination" 
                                                        class="appearance-none rounded-lg border border-gray-300 bg-white py-2.5 pl-4 pr-10 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all duration-200 hover:border-indigo-400 cursor-pointer"
                                                        onchange="this.form.submit()">
                                                    @foreach([5, 10, 15, 20] as $value)
                                                        <option value="{{ $value }}" {{ request('numPagination') == $value ? 'selected' : '' }}>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </form>
                                        </div>
                                        <span class="text-xs text-gray-500 italic sm:hidden">
                                            Mise à jour : {{ now()->format('d M Y') }}
                                        </span>
                                    </div>
                                </section>
                            
                                <!-- Apartment Listings -->
                                <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                                    @foreach($apartments as $apartment)
                                        <article class="bg-white rounded-xl shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                                            <div class="relative group">
                                                <img src="{{ $apartment['primaryImage']['image_url'] }}" alt="{{ $apartment['title'] }}" class="w-full h-56 object-cover transition-opacity duration-300 group-hover:opacity-90">
                                                <form method="POST" action="{{ route('favorites.toggle', $apartment['id']) }}" class="absolute top-3 right-3">
                                                    @csrf
                                                    <button type="submit" class="p-2 bg-white rounded-full shadow-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200">
                                                        @auth
                                                            @if($apartment['isFavorited'])
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-red-500">
                                                                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                                </svg>
                                                            @endif
                                                        @else
                                                            <a href="{{ route('login') }}" class="text-gray-500">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                                </svg>
                                                            </a>
                                                        @endauth
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="p-5">
                                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-1">{{ $apartment['title'] }}</h3>
                                                <p class="text-gray-600 text-sm mt-1 line-clamp-1">{{ $apartment['location'] }}</p>
                                                <div class="mt-3 flex items-center justify-between">
                                                    <span class="text-indigo-700 font-bold text-lg">{{ $apartment['price'] }}€ /nuit</span>
                                                    <span class="text-sm text-gray-500">{{ $apartment['bedrooms'] }} chambres</span>
                                                </div>
                                                <a href="{{ route('hébergements.show', $apartment['id']) }}" class="mt-4 block w-full text-center bg-indigo-600 text-white px-4 py-2.5 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-200 text-sm font-medium">
                                                    Voir les détails
                                                </a>
                                            </div>
                                        </article>
                                    @endforeach
                                </section>
                            
                                <!-- Custom Tailwind Pagination -->
                                <nav class="mt-8 max-w-6xl mx-auto flex items-center justify-between" aria-label="Pagination">
                                    <div class="hidden sm:block">
                                        <p class="text-sm text-gray-700">
                                            Affichage de <span class="font-medium">{{ $apartments->firstItem() }}</span> à 
                                            <span class="font-medium">{{ $apartments->lastItem() }}</span> sur 
                                            <span class="font-medium">{{ $apartments->total() }}</span> résultats
                                        </p>
                                    </div>
                                    <div class="flex justify-center gap-2 sm:gap-1">
                                        <!-- Previous Button -->
                                        <a href="{{ $apartments->previousPageUrl() }}" 
                                           class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                           {{ $apartments->onFirstPage() ? 'disabled' : '' }}>
                                            Précédent
                                        </a>
                                        <!-- Page Numbers (Simplified) -->
                                        @foreach($apartments->getUrlRange(1, $apartments->lastPage()) as $page => $url)
                                            <a href="{{ $url }}"
                                               class="px-3 py-2 text-sm font-medium {{ $apartments->currentPage() === $page ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700' }} border border-gray-300 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                                                {{ $page }}
                                            </a>
                                        @endforeach
                                        <!-- Next Button -->
                                        <a href="{{ $apartments->nextPageUrl() }}" 
                                           class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                           {{ $apartments->hasMorePages() ? '' : 'disabled' }}>
                                            Suivant
                                        </a>
                                    </div>
                                </nav>
                            </div>
                    </div>
                </div>
            
                <div id="cities-data" 
                     data-cities="{{ json_encode($citiesByCountry) }}"
                     data-selected-city="{{ request('city') }}">
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/city-filter.js') }}"></script>
</x-app-layout>
