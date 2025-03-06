<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Loading State -->
        <div id="loading-state" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-blue-500"></div>
        </div>

        <!-- Error Alert -->
        <div id="error-alert" class="hidden fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50" role="alert">
            <span id="error-message" class="block sm:inline"></span>
        </div>

        <div class="max-w-4xl mx-auto">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between relative">
                    <div class="w-full absolute top-1/2 transform -translate-y-1/2">
                        <div class="h-1 bg-gray-200">
                            <div class="h-1 bg-blue-500 transition-all duration-500" style="width: 50%"></div>
                        </div>
                    </div>
                    <div class="relative flex flex-col items-center">
                        <div class="rounded-full bg-blue-500 text-white w-8 h-8 flex items-center justify-center z-10">1</div>
                        <span class="mt-2 text-sm">Détails</span>
                    </div>
                    <div class="relative flex flex-col items-center">
                        <div class="rounded-full bg-blue-500 text-white w-8 h-8 flex items-center justify-center z-10">2</div>
                        <span class="mt-2 text-sm">Paiement</span>
                    </div>
                    <div class="relative flex flex-col items-center">
                        <div class="rounded-full bg-gray-200 text-gray-600 w-8 h-8 flex items-center justify-center z-10">3</div>
                        <span class="mt-2 text-sm">Confirmation</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Booking Summary -->
                <div class="border-b border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-2xl font-bold text-gray-800">Détails de la réservation</h1>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm" id="booking-status">
                                
                            </span>
                        </div>
                        <p class="text-gray-600">Réservation #<span id="booking-id" class="font-mono"></span></p>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-6 space-y-8">
                    <!-- Booking Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <h2 class="text-xl font-semibold">Détails du séjour</h2>
                            <div class="space-y-2">
                                <p><span class="font-medium">Check-in:</span> <span id="check-in"></span></p>
                                <p><span class="font-medium">Check-out:</span> <span id="check-out"></span></p>
                                <p><span class="font-medium">Nombre de voyageurs:</span> <span id="guests"></span></p>
                            </div>
                        </div>
    
                        <!-- Informations sur le voyageur -->
                        <div class="space-y-4">
                            <h2 class="text-xl font-semibold">Informations du voyageur</h2>
                            <div class="space-y-2">
                                <p><span class="font-medium">Nom:</span> <span id="guest-name"></span></p>
                                <p><span class="font-medium">Email:</span> <span id="guest-email"></span></p>
                                <p><span class="font-medium">Téléphone:</span> <span id="guest-phone"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-6">Paiement sécurisé</h2>
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-6">
                            <!-- Ajout du total à payer -->
                            <div class="mb-4 text-white">
                                <h3 class="text-lg font-semibold">Total à payer:</h3>
                                <p class="text-2xl font-bold" id="payment-amount"></p>
                            </div>
                            
                            <form id="payment-form" class="space-y-6">
                                <div class="payment-input-group">
                                    <label class="block text-white mb-2">Carte de crédit</label>
                                    <div id="card-element" class="w-full rounded-lg">
                                        <!-- Stripe Elements sera injecté ici -->
                                    </div>
                                    <div id="card-errors" class="text-red-200 text-sm mt-2" role="alert"></div>
                                </div>

                                <button type="submit" class="w-full bg-white text-indigo-600 font-semibold py-4 rounded-lg hover:bg-gray-50 transition duration-300 flex items-center justify-center space-x-2">
                                    <span>Payer</span>
                                    <span class="font-mono" id="button-amount"></span>
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .StripeElement {
            background-color: white;
            padding: 12px;
            border-radius: 8px;
        }
    </style>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const PaymentApp = {
            stripe: null,
            card: null,
            bookingData: null,

            init() {
                this.loadingState = document.getElementById('loading-state');
                this.errorAlert = document.getElementById('error-alert');
                this.errorMessage = document.getElementById('error-message');
                // Change this line to get ID from URL path
                this.bookingId = window.location.pathname.split('/').pop();
                
                this.setupEventListeners();
                this.loadBookingDetails();
                
                this.stripe = Stripe('{{ config('services.stripe.key') }}');
                const elements = this.stripe.elements();
                this.card = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#32325d',
                            '::placeholder': {
                                color: '#aab7c4'
                            }
                        },
                        invalid: {
                            color: '#fa755a',
                            iconColor: '#fa755a'
                        }
                    }
                });
                
                this.card.mount('#card-element');
                this.setupCardValidation();
            },

            setupEventListeners() {
                document.getElementById('payment-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handlePayment(e);
                });
            },

            async loadBookingDetails() {
                try {
                    this.showLoading();
                    const response = await fetch(`/booking?booking_id=${this.bookingId}`);
                    if (!response.ok) throw new Error('Failed to load booking details');
                    
                    const booking = await response.json();
                    this.updateUI(booking);
                } catch (error) {
                    this.showError('Impossible de charger les détails de la réservation');
                } finally {
                    this.hideLoading();
                }
            },

            updateUI(booking) {
                // Format dates using French locale
                const dateOptions = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };

                document.getElementById('booking-id').textContent = booking.id;
                document.getElementById('booking-status').textContent = 'status : ' + booking.status;
                document.getElementById('check-in').textContent = new Date(booking.check_in)
                    .toLocaleDateString('fr-FR', dateOptions);
                document.getElementById('check-out').textContent = new Date(booking.check_out)
                    .toLocaleDateString('fr-FR', dateOptions);
                document.getElementById('guests').textContent = `${booking.guests} ${booking.guests > 1 ? 'personnes' : 'personne'}`;
                document.getElementById('guest-name').textContent = booking.guest_name;
                document.getElementById('guest-email').textContent = booking.guest_email;
                document.getElementById('guest-phone').textContent = booking.guest_phone;
                
                const formattedPrice = new Intl.NumberFormat('fr-FR', { 
                    style: 'currency', 
                    currency: 'EUR',
                    minimumFractionDigits: 2
                }).format(booking.total_price);
                
                document.getElementById('payment-amount').textContent = formattedPrice;
                document.getElementById('button-amount').textContent = formattedPrice;
                
                // Update booking status
                const statusElement = document.getElementById('booking-status');
                if (booking.status === 'confirmed') {
                    statusElement.textContent = 'Confirmé';
                    statusElement.classList.remove('bg-blue-100', 'text-blue-800');
                    statusElement.classList.add('bg-green-100', 'text-green-800');
                }
            },

            showLoading() {
                this.loadingState.classList.remove('hidden');
            },

            hideLoading() {
                this.loadingState.classList.add('hidden');
            },

            showError(message) {
                this.errorMessage.textContent = message;
                this.errorAlert.classList.remove('hidden');
                setTimeout(() => {
                    this.errorAlert.classList.add('hidden');
                }, 5000);
            },

            setupCardValidation() {
                this.card.addEventListener('change', (event) => {
                    const displayError = document.getElementById('card-errors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });
            },

            async handlePayment(e) {
                e.preventDefault();
                this.showLoading();

                try {
                    const { paymentMethod, error } = await this.stripe.createPaymentMethod({
                        type: 'card',
                        card: this.card
                    });

                    if (error) {
                        throw new Error(error.message); 
                    }

                    const response = await fetch('/api/process-payment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json' // Ajout d'en-tête Accept
                        },
                        body: JSON.stringify({
                            booking_id: this.bookingId,
                            payment_method: 'stripe',
                            payment_method_id: paymentMethod.id
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || `Erreur ${response.status}: ${result.error || 'Erreur lors du traitement du paiement'}`);
                    }

                    if (result.status === 'success') {
                        window.location.href = `/reservation/confirmation/${this.bookingId}`;
                    } else {
                        throw new Error(result.message || 'Le paiement a échoué');
                    }
                } catch (error) {
                    console.error('Payment error:', error); // Ajout de log
                    this.showError(error.message);
                } finally {
                    this.hideLoading();
                }
            }
        };

        document.addEventListener('DOMContentLoaded', () => PaymentApp.init());
    </script>
</x-app-layout>

