<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between relative">
                    <div class="w-full absolute top-1/2 transform -translate-y-1/2">
                        <div class="h-1 bg-gray-200">
                            <div class="h-1 bg-blue-500 transition-all duration-500" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="relative flex flex-col items-center">
                        <div class="rounded-full bg-blue-500 text-white w-8 h-8 flex items-center justify-center z-10">1
                        </div>
                        <span class="mt-2 text-sm">Détails</span>
                    </div>
                    <div class="relative flex flex-col items-center">
                        <div class="rounded-full bg-blue-500 text-white w-8 h-8 flex items-center justify-center z-10">2
                        </div>
                        <span class="mt-2 text-sm">Paiement</span>
                    </div>
                    <div class="relative flex flex-col items-center">
                        <div class="rounded-full bg-blue-500 text-white w-8 h-8 flex items-center justify-center z-10">3
                        </div>
                        <span class="mt-2 text-sm">Confirmation</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Loading State -->
                <div id="loading-state" class="text-center py-6">
                    <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-blue-600 mx-auto"></div>
                    <p class="mt-3 text-gray-600 text-lg">Loading confirmation details...</p>
                </div>

                <!-- Booking Summary -->
                <div id="content" class="hidden border-b border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-2xl font-bold text-gray-800">Détails de la réservation</h1>
                            <span id="booking-status" class="px-3 py-1 rounded-full text-sm"></span>
                        </div>
                        <p class="text-gray-600">Réservation #<span id="booking-id" class="font-mono"></span></p>
                    </div>
                </div>

                <!-- Main Content -->
                <div id="details" class="hidden p-6 space-y-8">
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

                        <!-- Traveler Information -->
                        <div class="space-y-4">
                            <h2 class="text-xl font-semibold">Informations du voyageur</h2>
                            <div class="space-y-2">
                                <p><span class="font-medium">Nom:</span> <span id="guest-name"></span></p>
                                <p><span class="font-medium">Email:</span> <span id="guest-email"></span></p>
                                <p><span class="font-medium">Téléphone:</span> <span id="guest-phone"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">Informations de l'utilisateur</h2>
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden">
                                <img id="user-avatar" src="" alt="User Avatar" class="w-full h-full object-cover"
                                    onerror="this.src='/images/default-avatar.png'">
                            </div>
                            <div class="space-y-2">
                                <p><span class="font-medium">Nom:</span> <span id="user-name"></span></p>
                                <p><span class="font-medium">Email:</span> <span id="user-email"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Property Information -->
                    <div>
                        <h2 class="text-xl font-semibold mb-6">Les données de votre hébergement</h2>
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="text-white">
                                    <h3 id="property-title" class="text-xl font-semibold mb-4"></h3>
                                    <div class="space-y-2">
                                        <p class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                            </svg>
                                            <span id="property-address"></span>
                                        </p>
                                        <p class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                            </svg>
                                            <span id="property-capacity"></span>
                                        </p>
                                    </div>
                                </div>

                                <div class="text-white">
                                    <h4 class="font-semibold mb-2">Détails du prix</h4>
                                    <div class="space-y-1">
                                        <p class="flex justify-between">
                                            <span>Prix par nuit:</span>
                                            <span id="price-per-night"></span>
                                        </p>
                                        <p class="flex justify-between">
                                            <span>Nombre de nuits:</span>
                                            <span id="number-of-nights"></span>
                                        </p>
                                        <p
                                            class="flex justify-between font-semibold border-t border-white/30 pt-2 mt-2">
                                            <span>Prix total:</span>
                                            <span id="total-price"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-white/30 text-white">
                                <h4 class="font-semibold mb-2">Information sur l'hôte</h4>
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                                        <img id="host-image" src="" alt="Photo de l'hôte"
                                            class="w-full h-full object-cover"
                                            onerror="this.src='/images/default-avatar.png'">
                                    </div>
                                    <div>
                                        <p class="font-medium" id="host-name"></p>
                                        <p class="text-sm opacity-80" id="host-rating"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Download Button -->
                        <div class="mt-8 flex justify-center">
                            <a id="downloadDetails"
                                class="flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all transform hover:scale-[1.02]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Télécharger les détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const bookingId = window.location.pathname.split('/').pop(); // Replace with dynamic ID if needed, e.g., from URL
            loadData(bookingId);

            async function loadData(id) {
                try {
                    const response = await $.ajax({
                        url: `/booking/${id}/confirmation`, // Adjust base URL if needed
                        method: 'GET',
                        timeout: 10000
                    });

                    if (response.success) {
                        updateUI(response.data);
                    } else {
                        showError('Invalid response from server');
                    }
                } catch (error) {
                    showError('Failed to load confirmation details. Please try again later.');
                    console.error('Error:', error);
                }
            }

            function updateUI(data) {
                const { user, reservation, property } = data;

                // Show content, hide loading
                $('#loading-state').addClass('hidden');
                $('#content').removeClass('hidden');
                $('#details').removeClass('hidden');

                // Booking Data
                $('#booking-id').text(reservation.id);
                $('#downloadDetails').attr('href', '/booking/download-pdf/' + reservation.id);
                $('#booking-status').text(capitalize(reservation.status))
                    .addClass(reservation.status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800');
                $('#check-in').text(formatDate(reservation.check_in));
                $('#check-out').text(formatDate(reservation.check_out));
                $('#guests').text(reservation.guests);
                $('#guest-name').text(sanitize(reservation.guest_name));
                $('#guest-email').text(sanitize(reservation.guest_email));
                $('#guest-phone').text(sanitize(reservation.guest_phone));
                $('#total-price').text(`$${formatPrice(reservation.total_price)}`);

                // User Data
                $('#user-name').text(sanitize(user.name));
                $('#user-email').text(sanitize(user.email));
                $('#user-avatar').attr('src', user.avatar);

                // Property Data
                $('#property-title').text(sanitize(property.title));
                $('#property-address').text(sanitize(property.location));
                $('#property-capacity').text(`${property.max_guests} voyageurs maximum`);
                $('#price-per-night').text(`$${formatPrice(property.price)}`);
                const nights = Math.ceil((new Date(reservation.check_out) - new Date(reservation.check_in)) / (1000 * 60 * 60 * 24));
                $('#number-of-nights').text(nights);
                $('#host-name').text(sanitize(user.name)); // Assuming host is the same as user; adjust if different
                $('#host-image').attr('src', user.avatar);
                $('#host-rating').text(property.rating ? `${property.rating} étoiles` : 'Pas encore noté');
            }

            // Helper Functions
            function sanitize(str) {
                return $('<div/>').text(str).html();
            }

            function capitalize(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            function formatPrice(price) {
                return Number(price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function formatDate(dateStr) {
                return new Date(dateStr).toLocaleDateString('fr-FR', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
            }

            function showError(message) {
                $('#loading-state').addClass('hidden');
                $('#content').addClass('hidden');
                $('#details').addClass('hidden');
                $('#error-state').removeClass('hidden');
                $('#error-message').text(message);
            }
        });
    </script>
</x-app-layout>