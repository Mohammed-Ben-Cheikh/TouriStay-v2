<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="container" class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300">
                <!-- Loading State -->
                <div id="loading-state" class="text-center py-6">
                    <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-blue-600 mx-auto"></div>
                    <p class="mt-3 text-gray-600 text-lg">Loading booking details...</p>
                </div>

                <!-- Main Content -->
                <div id="content" class="hidden">
                    <div class="p-6 md:p-8">
                        <!-- Booking Details Section -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Booking Details</h1>
                            <div id="booking-container" class="space-y-6">
                                <div id="booking-details" class="border rounded-lg p-6 shadow-sm">
                                    <div class="flex justify-between items-center mb-4">
                                        <span id="booking-id" class="text-xl font-semibold text-gray-800"></span>
                                        <span id="booking-status" class="px-4 py-1 rounded-full text-sm font-medium"></span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p><span class="font-medium text-gray-600">Guest Name:</span> <span id="guest-name"></span></p>
                                            <p><span class="font-medium text-gray-600">Email:</span> <span id="guest-email"></span></p>
                                            <p><span class="font-medium text-gray-600">Phone:</span> <span id="guest-phone"></span></p>
                                            <p><span class="font-medium text-gray-600">Guests:</span> <span id="guests"></span></p>
                                        </div>
                                        <div>
                                            <p><span class="font-medium text-gray-600">Check-in:</span> <span id="check-in"></span></p>
                                            <p><span class="font-medium text-gray-600">Check-out:</span> <span id="check-out"></span></p>
                                            <p><span class="font-medium text-gray-600">Total Price:</span> <span id="total-price"></span></p>
                                            <p><span class="font-medium text-gray-600">Booked On:</span> <span id="created-at"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Summary Section -->
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Property Summary</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="relative h-64">
                                    <img id="property-image" 
                                         class="w-full h-full object-cover rounded-lg transition-opacity duration-300" 
                                         alt="" 
                                         loading="lazy">
                                </div>
                                <div class="space-y-4">
                                    <h3 id="property-title" class="text-xl font-semibold text-gray-800"></h3>
                                    <p id="property-location" class="text-gray-600"></p>
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex">
                                            <dt class="font-medium text-gray-600 w-24">Type:</dt>
                                            <dd id="property-type"></dd>
                                        </div>
                                        <div class="flex">
                                            <dt class="font-medium text-gray-600 w-24">Price:</dt>
                                            <dd id="property-price"></dd>
                                        </div>
                                        <div class="flex">
                                            <dt class="font-medium text-gray-600 w-24">Bedrooms:</dt>
                                            <dd id="property-bedrooms"></dd>
                                        </div>
                                        <div class="flex">
                                            <dt class="font-medium text-gray-600 w-24">Max Guests:</dt>
                                            <dd id="property-max-guests"></dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Amenities</h4>
                                <div id="property-amenities" class="grid grid-cols-2 gap-3 text-sm"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error State -->
                <div id="error-state" class="hidden text-red-600 text-center p-6 bg-red-50 rounded-lg">
                    <p id="error-message" class="font-medium"></p>
                    <button class="mt-4 px-4 py-2 bg-red-100 rounded hover:bg-red-200 transition-colors" onclick="loadData()">Retry</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            loadData();

            async function loadData() {
                try {
                    const response = await $.ajax({
                        url: '/admin/booking/' + window.location.pathname.split('/').pop(), // Adjust base URL if needed (e.g., http://touristay-v2.test/booking/10)
                        method: 'GET',
                        timeout: 10000
                    });

                    if (response.success) {
                        updateUI(response.data);
                    } else {
                        showError('Invalid response from server');
                    }
                } catch (error) {
                    showError('Failed to load booking details. Please try again later.');
                    console.error('Error:', error);
                }
            }

            function updateUI(data) {
                const { booking, property } = data;

                // Show content, hide loading/error
                $('#loading-state').addClass('hidden');
                $('#content').removeClass('hidden');
                $('#error-state').addClass('hidden');

                // Booking Data
                $('#booking-details').addClass(booking.status === 'confirmed' ? 'bg-green-50' : 'bg-yellow-50');
                $('#booking-id').text(`Booking #${booking.id}`);
                $('#booking-status').text(capitalize(booking.status))
                                   .addClass(booking.status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800');
                $('#guest-name').text(sanitize(booking.guest_name));
                $('#guest-email').text(sanitize(booking.guest_email));
                $('#guest-phone').text(sanitize(booking.guest_phone));
                $('#guests').text(booking.guests);
                $('#check-in').text(formatDate(booking.check_in));
                $('#check-out').text(formatDate(booking.check_out));
                $('#total-price').text(`$${formatPrice(booking.total_price)}`);
                $('#created-at').text(formatDate(booking.created_at));

                // Property Data (Summary)
                $('#property-image').attr('src', `http://touristay-v2.test/${property.primary_image.image_url}`)
                                   .attr('alt', sanitize(property.title));
                $('#property-title').text(sanitize(property.title));
                $('#property-location').text(sanitize(property.location));
                $('#property-type').text(capitalize(property.type));
                $('#property-price').text(`$${formatPrice(property.price)} / night`);
                $('#property-bedrooms').text(property.bedrooms);
                $('#property-max-guests').text(property.max_guests);

                // Amenities
                const $amenitiesContainer = $('#property-amenities').empty();
                Object.entries(property.equipments)
                    .filter(([_, available]) => available === "1")
                    .forEach(([equipment]) => {
                        $amenitiesContainer.append(`
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">${capitalize(equipment)}</span>
                            </div>
                        `);
                    });

                $('#container').removeClass('opacity-0').addClass('opacity-100');
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
                return new Date(dateStr).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
            }

            function showError(message) {
                $('#loading-state').addClass('hidden');
                $('#content').addClass('hidden');
                $('#error-state').removeClass('hidden');
                $('#error-message').text(message);
            }
        });
    </script>
</x-app-layout>