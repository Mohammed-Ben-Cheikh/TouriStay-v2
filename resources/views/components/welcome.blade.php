<style>
    .filter-btn.active {
        color: rgb(79 70 229);
        /* indigo-600 */
        font-weight: 600;
    }

    .scrollbar-thin {
        scrollbar-width: thin;
    }

    .scrollbar-thumb-indigo-200 {
        scrollbar-color: #c7d2fe #f3f4f6;
    }

    .scrollbar-track-gray-50 {
        background: #f3f4f6;
    }

    .scrollbar-thin::-webkit-scrollbar {
        height: 8px;
        /* Hauteur pour un scroll horizontal */
    }

    .scrollbar-thin::-webkit-scrollbar-thumb {
        background-color: #c7d2fe;
        border-radius: 4px;
    }

    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f3f4f6;
    }
</style>

<div class="relative bg-indigo-700 h-[500px]">
    <!-- Image de fond -->
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb" alt="Background"
            class="w-full h-full object-cover opacity-40">
    </div>

    <!-- Contenu Hero -->
    <div class="relative container mx-auto px-4 py-16">
        <div class="text-center text-white mb-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Trouvez votre hébergement idéal</h1>
            <p class="text-xl opacity-90">Des milliers de locations de vacances vous attendent</p>
        </div>

        <!-- Barre de recherche -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-4">
                <form action="{{ route('hébergements.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                        <select name="city" class="w-full px-4 py-2 border rounded-md">
                            <option value="">Toutes les villes</option>
                            @foreach($data['villes'] as $ville)
                                <option value="{{ $ville->id }}">{{ $ville->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type de logement</label>
                        <select name="type" class="w-full px-4 py-2 border rounded-md">
                            <option value="">Tous les types</option>
                            <option value="apartment">Appartement</option>
                            <option value="house">Maison</option>
                            <option value="studio">Studio</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Voyageurs</label>
                        <select name="max_guests" class="w-full px-4 py-2 border rounded-md">
                            <option value="">Nombre de voyageurs</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i > 1 ? 'personnes' : 'personne' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Rechercher
                        </button>
                    </div>
                </form>

                <!-- Filtres rapides -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('hébergements.index', ['price' => '0-100']) }}"
                        class="px-4 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-full">
                        Prix < 100€ </a>
                            <a href="{{ route('hébergements.index', ['equipments' => ['wifi']]) }}"
                                class="px-4 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-full">
                                Wifi gratuit
                            </a>
                            <a href="{{ route('hébergements.index', ['equipments' => ['aircon']]) }}"
                                class="px-4 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-full">
                                Climatisation
                            </a>
                            <a href="{{ route('hébergements.index', ['equipments' => ['parking']]) }}"
                                class="px-4 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-full">
                                Parking gratuit
                            </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
    <h1 class="mt-8 text-2xl font-medium text-gray-900">
        Bienvenue sur TouriStay
    </h1>

    <p class="mt-6 text-gray-500 leading-relaxed">
        Découvrez notre sélection d'appartements disponibles pour votre prochain séjour.
    </p>
</div>

<div class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($data['property'] as $prop)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <!-- Image Section -->
                    <div class="relative group">
                        <img src="{{ $prop->primaryImage?->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267' }}"
                            alt="{{ $prop->title }}"
                            class="w-full h-56 object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <span
                            class="absolute top-3 right-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-3 py-1 rounded-full text-sm font-medium shadow">
                            {{ $prop->is_available ? 'Disponible' : 'Non disponible' }}
                        </span>
                    </div>

                    <!-- Content Section -->
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-900 line-clamp-1 hover:text-indigo-600 transition-colors">
                            {{ $prop->title }}
                        </h3>
                        <p class="mt-2 text-gray-600 text-sm line-clamp-2">
                            {{ Str::limit($prop->description, 100) }}
                        </p>

                        <!-- Stats -->
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $prop->bedrooms }} chambres
                            </div>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                {{ $prop->max_guests ?? 4 }} pers.
                            </div>
                        </div>

                        <!-- Price & Rating -->
                        <div class="mt-5 flex items-center justify-between">
                            <div>
                                <span class="text-xl font-bold text-indigo-600">{{ number_format($prop->price, 2) }}€</span>
                                <span class="text-sm text-gray-500">/nuit</span>
                                <p class="text-xs text-gray-400">Min. {{ $prop->minimum_nights ?? 1 }} nuits</p>
                            </div>
                            @if($prop->rating)
                                <div class="flex items-center bg-yellow-50 px-2 py-1 rounded-full">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">{{ $prop->rating }}
                                        ({{ $prop->reviews_count }})</span>
                                </div>
                            @endif
                        </div>

                        <!-- Equipments -->
                        @if($prop->equipments)
                            <div class="mt-5">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Équipements</h4>
                                <div
                                    class="flex gap-2 overflow-x-auto scrollbar-thin scrollbar-thumb-indigo-200 scrollbar-track-gray-50 p-2 rounded-xl">
                                    @foreach($prop->equipments as $equipment => $available)
                                        <span
                                            class="inline-flex items-center flex-shrink-0 text-xs bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-full font-medium border border-indigo-100 shadow-sm">
                                            <svg class="w-3 h-3 mr-1 text-indigo-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ ucfirst(str_replace('_', ' ', $equipment)) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Button & Availability -->
                        <div class="mt-5 space-y-3">
                            <a href="{{ route('hébergements.show', $prop['id']) }}"
                                class="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors duration-200 font-medium">
                                Voir les détails
                            </a>
                            <!-- Disponibilité -->
                            <div class="mt-4 text-sm text-gray-500">
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    disponibilité: {{ Carbon\Carbon::parse($prop->available_from)->format('d F') }} -
                                    {{ Carbon\Carbon::parse($prop->available_to)->format('d F') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<!-- Galerie des Pays -->
<div class="mt-12 p-6 lg:p-8 bg-white border-b border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Découvrez par Pays</h2>
        <div class="flex gap-2">
            <button class="p-2 rounded-full bg-gray-100 hover:bg-gray-200" id="prevCountry">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button class="p-2 rounded-full bg-gray-100 hover:bg-gray-200" id="nextCountry">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($data['citiesByCountry'] as $country)
            <div class="group relative rounded-xl overflow-hidden cursor-pointer transform transition hover:scale-105">
                <img src="{{ $country->image_url }}" alt="{{ $country->nom }}" class="w-full h-64 object-cover">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent group-hover:from-black/90">
                    <div class="absolute bottom-4 left-4 right-4">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $country->nom }}</h3>
                        <div class="flex justify-between items-center text-white/90">
                            <span>{{ count($country->villes) }} propriétés</span>
                            <span class="bg-white/20 px-3 py-1 rounded-full text-sm">À partir de 85€</span>
                        </div>
                        <div class="mt-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @foreach($country->villes->take(3) as $ville)
                                <span class="text-xs bg-white/20 px-2 py-1 rounded-full">{{ $ville->nom }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Galerie des Villes -->
<div class="p-6 lg:p-8 bg-gray-50">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Villes en Tendance</h2>
        <div class="flex gap-4" id="filterButtons">
            <button class="text-sm font-medium text-gray-600 hover:text-indigo-600 filter-btn active"
                data-filter="all">Toutes</button>
            @foreach($data['citiesByCountry'] as $country)
                <button class="text-sm font-medium text-gray-600 hover:text-indigo-600 filter-btn"
                    data-filter="{{ strtolower($country->nom) }}">
                    {{ $country->nom }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="citiesGrid">
        @foreach($data['villes'] as $ville)
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition city-card"
                data-country="{{ strtolower($data['citiesByCountry']->where('id', $ville->pays_id)->first()->nom) }}"
                style="display: block;">
                <div class="relative">
                    <img src="{{ $ville->image_url }}" alt="{{ $ville->nom }}" class="w-full h-40 object-cover">
                    <div class="absolute top-2 right-2 bg-white/90 px-2 py-1 rounded-full text-xs font-medium">
                        {{ random_int(50, 200) }} locations
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $ville->nom }}</h3>
                            <p class="text-sm text-gray-600">
                                {{ $data['citiesByCountry']->where('id', $ville->pays_id)->first()->nom }}</p>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="ml-1 text-sm text-gray-600">{{ number_format(random_int(40, 50) / 10, 1) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 text-center">
        <button id="loadMoreBtn" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
            Voir plus de villes
        </button>
    </div>
</div>

<!-- Section Blog -->
<div class="p-6 lg:p-8 bg-white">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Derniers Articles Blog</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <article class="rounded-lg overflow-hidden shadow-lg">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836" alt="Blog post"
                class="w-full h-48 object-cover">
            <div class="p-4">
                <span class="text-indigo-600 text-sm font-semibold">Conseils Voyage</span>
                <h3 class="mt-2 text-xl font-semibold">Les meilleurs quartiers de Paris</h3>
                <p class="mt-2 text-gray-600 text-sm">Découvrez les quartiers les plus authentiques de Paris pour votre
                    prochain séjour...</p>
                <a href="#" class="mt-4 inline-flex items-center text-indigo-600 hover:text-indigo-700">
                    Lire plus
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </article>
        <!-- Plus d'articles... -->
    </div>
</div>

<!-- Section Avis Clients -->
<div class="p-6 lg:p-8 bg-gray-50">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Ce que disent nos clients</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center mb-4">
                <img src="https://i.pravatar.cc/60?img=1" alt="Avatar" class="w-12 h-12 rounded-full">
                <div class="ml-4">
                    <h4 class="font-semibold">Marie Dupont</h4>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-sm text-gray-600 ml-1">5.0</span>
                    </div>
                </div>
            </div>
            <p class="text-gray-600">"Séjour parfait ! L'appartement était exactement comme sur les photos..."</p>
        </div>
        <!-- Plus d'avis... -->
    </div>
</div>

<!-- Section Newsletter -->
<div class="p-6 lg:p-8 bg-indigo-600 text-white">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-2xl font-bold mb-4">Restez informé des meilleures offres</h2>
        <p class="mb-6">Inscrivez-vous à notre newsletter pour recevoir nos meilleures offres</p>
        <form class="flex gap-2 max-w-md mx-auto">
            <input type="email" placeholder="Votre email" class="flex-1 px-4 py-2 rounded-md text-gray-900">
            <button type="submit" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-md hover:bg-gray-100">
                S'inscrire
            </button>
        </form>
    </div>
</div>

<script src="{{ asset('js/cities.js') }}"></script>