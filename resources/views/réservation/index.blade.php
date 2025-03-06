<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Mes Réservations</h2>
                
                @if($reservations->isEmpty())
                    <p class="text-gray-500">Vous n'avez aucune réservation pour le moment.</p>
                @else
                <div class="grid grid-cols-1 gap-6">
                    @foreach($reservations as $reservation)
                        <div class="border rounded-lg p-6 hover:shadow-lg transition duration-300">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                                <div class="space-y-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $reservation->property->name }}</h3>
                                        <span class="px-4 py-1.5 rounded-full text-sm font-medium
                                            @if($reservation->status === 'confirmed') 
                                                bg-green-100 text-green-800
                                            @elseif($reservation->status === 'pending')
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <p class="text-gray-600 flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ \Carbon\Carbon::parse($reservation->check_in)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($reservation->check_out)->format('d/m/Y') }}</span>
                                            </p>
                                            <p class="text-gray-600 flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <span>{{ $reservation->guests }} {{ Str::plural('personne', $reservation->guests) }}</span>
                                            </p>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <p class="text-gray-600 flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span>{{ $reservation->guest_name }}</span>
                                            </p>
                                            <p class="text-gray-600 flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ $reservation->guest_email }}</span>
                                            </p>
                                            <p class="text-gray-600 flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span>{{ $reservation->guest_phone }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-end justify-between gap-4">
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Total</p>
                                        <p class="text-2xl font-bold text-primary-600">{{ number_format($reservation->total_price, 2) }}€</p>
                                    </div>
                                    @if($reservation->status === 'pending')
                                        <a href="{{ route('payment.process', $reservation) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            Payer maintenant
                                        </a>
                                    @else
                                    <div class="flex space-x-3">
                                        <a href="{{ route('booking.confirmation', $reservation) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-black rounded-md transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Détails
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
