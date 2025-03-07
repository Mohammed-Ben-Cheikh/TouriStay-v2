<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="property-container"
                class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300">
                <div class="text-center py-6" id="loading-state">
                    <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-blue-600 mx-auto"></div>
                    <p class="mt-3 text-gray-600 text-lg">Loading property details...</p>
                </div>

                <div id="property-content" class="hidden">
                    <div class="relative h-96 md:h-[500px]">
                        <img id="property-image" class="w-full h-full object-cover transition-opacity duration-300"
                            alt="" loading="lazy">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent p-6 flex items-end">
                            <div>
                                <h1 id="property-title"
                                    class="text-3xl md:text-4xl font-bold text-white drop-shadow-lg"></h1>
                                <p id="property-location" class="text-gray-200 text-lg mt-1"></p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 md:p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Property Details</h2>
                                <dl class="space-y-4">
                                    <div class="flex">
                                        <dt class="font-medium text-gray-600 w-32">Type:</dt>
                                        <dd id="property-type"></dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="font-medium text-gray-600 w-32">Price:</dt>
                                        <dd id="property-price"></dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="font-medium text-gray-600 w-32">Bedrooms:</dt>
                                        <dd id="property-bedrooms"></dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="font-medium text-gray-600 w-32">Max Guests:</dt>
                                        <dd id="property-max-guests"></dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="font-medium text-gray-600 w-32">Min Nights:</dt>
                                        <dd id="property-min-nights"></dd>
                                    </div>
                                </dl>

                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Amenities</h3>
                                    <div id="property-amenities" class="grid grid-cols-2 gap-4"></div>
                                </div>
                            </div>

                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2 mb-6">Booking History</h2>
                                <div class="mb-4 relative">
                                    <input type="text" id="booking-search"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10"
                                        placeholder="Search by ID, dates, or status...">
                                    <svg class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div id="bookings-container" class="space-y-4 max-h-[400px] overflow-y-auto"></div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2 mb-4">Description</h2>
                            <p id="property-description" class="text-gray-700 leading-relaxed"></p>
                        </div>
                    </div>
                </div>

                <div id="error-state" class="hidden text-red-600 text-center p-6 bg-red-50 rounded-lg">
                    <p id="error-message" class="font-medium"></p>
                    <button class="mt-4 px-4 py-2 bg-red-100 rounded hover:bg-red-200 transition-colors"
                        onclick="loadPropertyData()">Retry</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function gopage(arg) {
            window.location.href = '/admin/reservation/' + arg;
        }

        $(document).ready(function () {
            const propertyId = window.location.pathname.split('/').pop();
            const $propertyContainer = $('#property-container');
            let allBookings = [];

            // Initial load
            loadPropertyData();

            async function loadPropertyData() {
                try {
                    const response = await $.ajax({
                        url: `http://touristay-v2.test/admin/reservations?property_id=${propertyId}`,
                        method: 'GET',
                        timeout: 10000
                    });

                    if (response.success) {
                        updatePropertyUI(response.data.property);
                        initializeSearch();
                    } else {
                        showError('Invalid response from server');
                    }
                } catch (error) {
                    showError('Failed to load property details. Please try again later.');
                    console.error('Error loading property:', error);
                }
            }

            function updatePropertyUI(property) {
                allBookings = property.bookings || [];

                // Show content, hide loading/error states
                $('#loading-state').addClass('hidden');
                $('#property-content').removeClass('hidden');
                $('#error-state').addClass('hidden');

                // Populate property details
                $('#property-image').attr('src', `http://touristay-v2.test/${property.primary_image.image_url}`)
                    .attr('alt', sanitize(property.title));
                $('#property-title').text(sanitize(property.title));
                $('#property-location').text(sanitize(property.location));
                $('#property-type').text(capitalize(property.type));
                $('#property-price').text(`$${formatPrice(property.price)} / night`);
                $('#property-bedrooms').text(property.bedrooms);
                $('#property-max-guests').text(property.max_guests);
                $('#property-min-nights').text(property.minimum_nights);
                $('#property-description').text(sanitize(property.description));

                // Populate amenities
                const $amenitiesContainer = $('#property-amenities').empty();
                Object.entries(property.equipments)
                    .filter(([_, available]) => available === "1")
                    .forEach(([equipment]) => {
                        $amenitiesContainer.append(`
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">${capitalize(equipment)}</span>
                            </div>
                        `);
                    });

                // Populate bookings
                updateBookingsUI(allBookings);

                $propertyContainer.removeClass('opacity-0').addClass('opacity-100');
            }

            function initializeSearch() {
                const $searchInput = $('#booking-search');
                const $bookingsContainer = $('#bookings-container');

                $searchInput.on('input', debounce(function () {
                    const searchTerm = $searchInput.val().toLowerCase().trim();
                    const filteredBookings = filterBookings(searchTerm);
                    updateBookingsUI(filteredBookings);

                    if (searchTerm && filteredBookings.length) {
                        highlightMatches(filteredBookings, searchTerm);
                    }
                }, 300));
            }

            function filterBookings(searchTerm) {
                if (!searchTerm) return allBookings;

                return allBookings.filter(booking => {
                    const idMatch = booking.id.toString().includes(searchTerm);
                    const checkInMatch = formatDate(booking.check_in).toLowerCase().includes(searchTerm);
                    const checkOutMatch = formatDate(booking.check_out).toLowerCase().includes(searchTerm);
                    const statusMatch = booking.status.toLowerCase().includes(searchTerm);
                    return idMatch || checkInMatch || checkOutMatch || statusMatch;
                });
            }

            function highlightMatches(bookings, searchTerm) {
                bookings.forEach(booking => {
                    const $bookingElement = $bookingsContainer.find(`[data-booking-id="${booking.id}"]`).closest('.border');
                    if ($bookingElement.length) {
                        $bookingElement.addClass('ring-2 ring-blue-500 scale-102 transition-all duration-200');
                        setTimeout(() => $bookingElement.removeClass('scale-102'), 200);
                    }
                });
            }

            function updateBookingsUI(bookings) {
                const $bookingsContainer = $('#bookings-container').empty();

                if (!bookings?.length) {
                    $bookingsContainer.html('<div class="text-gray-500 text-center py-4">No bookings found</div>');
                    return;
                }

                bookings.forEach(booking => {
                    $bookingsContainer.append(`
                        <div onclick="gopage('${booking.id}')" class="border rounded-lg p-4 ${booking.status === 'confirmed' ? 'bg-green-50' : 'bg-yellow-50'} hover:shadow-md transition-all duration-300">
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-medium text-gray-800 hover:text-blue-600 transition-colors cursor-pointer" 
                                      onclick="navigator.clipboard.writeText('${booking.id}')"
                                      data-booking-id="${booking.id}">
                                    Booking #${booking.id}
                                </span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium ${booking.status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                        }">
                                    ${capitalize(booking.status)}
                                </span>
                            </div>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium text-gray-600">Check-in:</span> ${formatDate(booking.check_in)}</p>
                                <p><span class="font-medium text-gray-600">Check-out:</span> ${formatDate(booking.check_out)}</p>
                                <p><span class="font-medium text-gray-600">Guests:</span> ${booking.guests}</p>
                                <p><span class="font-medium text-gray-600">Total:</span> $${formatPrice(booking.total_price)}</p>
                            </div>
                        </div>
                    `);
                });
            }

            // Helper functions
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
                $('#property-content').addClass('hidden');
                $('#error-state').removeClass('hidden');
                $('#error-message').text(message);
            }

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Auto-refresh
            let refreshInterval = setInterval(loadPropertyData, 30000);
            $(window).on('unload', () => clearInterval(refreshInterval));
        });
    </script>
</x-app-layout>