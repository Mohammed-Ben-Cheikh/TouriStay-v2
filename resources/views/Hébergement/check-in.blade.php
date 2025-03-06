<x-app-layout>
    <!-- Add SweetAlert2 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <!-- Ajout des styles personnalisés pour le calendrier -->
    <style>
        .fc {
            font-size: 0.9em;
            background: white;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .fc .fc-toolbar-title {
            font-size: 1.2em;
            font-weight: 600;
        }

        .fc .fc-button {
            padding: 0.4rem 0.8rem;
            font-size: 0.9em;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            font-weight: 500;
        }

        .fc .fc-daygrid-day-number {
            padding: 0.3em 0.5em;
            font-size: 0.9em;
        }

        .fc .fc-daygrid-day-frame {
            min-height: 50px;
        }

        .fc .fc-day-today {
            background: rgba(59, 130, 246, 0.1) !important;
        }

        .fc .fc-highlight {
            background: rgba(59, 130, 246, 0.2) !important;
        }

        .fc .fc-event {
            font-size: 0.8em;
            border-radius: 3px;
        }

        .calendar-container {
            max-height: 600px;
            overflow: hidden;
        }

        /* Nouveaux styles */
        .calendar-wrapper {
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .fc .fc-toolbar {
            padding: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .fc .fc-daygrid-body {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .fc td,
        .fc th {
            border: 1px solid #e5e7eb !important;
        }

        .fc .fc-scrollgrid {
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #e5e7eb !important;
        }

        .fc .fc-button-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .fc .fc-button-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10">
                <h1 class="text-3xl font-bold text-center mb-8 text-blue-900">Réservation</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Calendrier à gauche avec nouveau wrapper -->
                    <div class="calendar-wrapper">
                        <h2 class="text-xl font-semibold mb-4">Sélectionnez vos dates</h2>
                        <div id="calendar" class="rounded-lg border shadow-sm bg-white"></div>
                    </div>

                    <!-- Formulaire à droite -->
                    <div class="p-6 bg-white rounded-lg shadow-sm flex items-center">
                        <form id="reservationForm" class="space-y-6 w-full" method="POST">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}">
                            <h2 class="text-xl font-semibold mb-4">Informations personnelles</h2>
                            <input id="check_in" type="hidden" name="check_in" value="">
                            <input id="check_out" type="hidden" name="check_out" value="">

                            <!-- Informations personnelles -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nom complet</label>
                                    <input type="text" name="full_name" required value="{{ $user->name }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" required value="{{ $user->email }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <input type="tel" name="phone" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <!-- After the phone input field, add the guests input -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre de voyageurs</label>
                                    <input type="number" name="guests" id="guests" required min="1" max="{{ $property->max_guests }}"
                                        value="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- Résumé de la réservation -->
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-lg font-medium mb-3">Résumé de la réservation</h3>
                                <div class="space-y-2">
                                    <p id="selectedDates" class="text-sm text-gray-600">Dates: Non sélectionnées</p>
                                    <p id="nightsCount" class="text-sm text-gray-600">Durée: - nuits</p>
                                    <p id="totalPrice" class="text-lg font-bold text-blue-600 mt-2">Total: - €</p>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all transform hover:scale-[1.02] disabled:opacity-50 disabled:hover:scale-100">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-check mr-2"></i>
                                    <span>Confirmer la réservation</span>
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/fr.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const PRICE_PER_NIGHT = {{ $property->price }}; // Get price from property
                        
                        // Add this function to recalculate total when guests change
                        function updateTotal() {
                            const nights = document.getElementById('nightsCount').textContent.split(':')[1].trim().split(' ')[0];
                            if (nights !== '-') {
                                const total = nights * PRICE_PER_NIGHT;
                                document.getElementById('totalPrice').textContent = `Total: ${total} €`;
                            }
                        }

                        // Add event listener for guests input
                        document.getElementById('guests').addEventListener('change', updateTotal);

                        const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                            initialView: 'dayGridMonth',
                            locale: 'fr',
                            selectable: true,
                            height: 'auto',
                            contentHeight: 500,
                            aspectRatio: 1.5,
                            buttonText: {
                                today: "Aujourd'hui"
                            },
                            dayMaxEvents: 2,
                            eventDisplay: 'block',
                            displayEventTime: false,
                            eventClassNames: 'text-xs px-1 py-0.5',
                            selectConstraint: {
                                start: new Date(),
                                end: '{{ $property->available_until ? $property->available_until->format('Y-m-d') : null }}'
                            },
                            validRange: {
                                start: new Date(),
                                end: '{{ $property->available_until ? $property->available_until->format('Y-m-d') : null }}' // Endpoint pour vérifier la disponibilité
                            },
                            selectOverlap: false,
                            eventOverlap: false,
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: false
                            },
                            select: function (info) {
                                const startDate = new Date(info.start);
                                const endDate = new Date(info.end);
                                const nights = Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24));
                                const guests = document.getElementById('guests').value;

                                document.getElementById('check_in').value = info.startStr;
                                document.getElementById('check_out').value = info.endStr;
                                document.getElementById('selectedDates').textContent =
                                    `Dates: ${startDate.toLocaleDateString('fr-FR')} - ${endDate.toLocaleDateString('fr-FR')}`;
                                document.getElementById('nightsCount').textContent = `Durée: ${nights} nuits`;
                                document.getElementById('totalPrice').textContent = `Total: ${nights * PRICE_PER_NIGHT * guests} €`;
                            },
                            events: `/reservations?check=bookingData&&property_id={{ $property->id }}`, // Endpoint pour récupérer les réservations existantes
                            eventContent: function (arg) {
                                return {
                                    html: `<div class="p-1 text-xs bg-red-500 text-white rounded">${arg.event.title}</div>`
                                };
                            }
                        });

                        calendar.render();

                        // Gestion du formulaire
                        document.getElementById('reservationForm').addEventListener('submit', async function (event) {
                            event.preventDefault();
                            const button = event.target.querySelector('button');
                            button.disabled = true;
                            button.innerHTML = '<span class="inline-flex items-center"><i class="fas fa-spinner fa-spin mr-2"></i>Traitement en cours...</span>';

                            try {
                                const formData = new FormData(event.target);
                                const response = await fetch('/reservations', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    }
                                });

                                // First check if the response is JSON
                                const contentType = response.headers.get('content-type');
                                if (contentType && contentType.includes('application/json')) {
                                    const result = await response.json();
                                    console.log(result);
                                    
                                    if (!response.ok) {
                                        throw new Error(result.message || 'Erreur lors de la réservation');
                                    }
                                    
                                    // Success case
                                    await Swal.fire({
                                        icon: 'success',
                                        title: 'Réservation confirmée !',
                                        text: 'Vous recevrez un email de confirmation sous peu.',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                    
                                    window.location.href = '/reservation/' + result.id;
                                } else {
                                    // If response is not JSON, get the text content
                                    const textResponse = await response.text();
                                    
                                    // Display error with the actual error message
                                    await Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur',
                                        text: 'Une erreur est survenue lors de la réservation.',
                                        html: textResponse // This will display the HTML error message in a formatted way
                                    });
                                    
                                    button.disabled = false;
                                    button.innerHTML = '<span class="inline-flex items-center"><i class="fas fa-check mr-2"></i>Confirmer la réservation</span>';
                                }
                            } catch (error) {
                                // console.log(error);
                                await Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: error.message || 'Une erreur est survenue. Veuillez réessayer.',
                                });
                                button.disabled = false;
                                button.innerHTML = '<span class="inline-flex items-center"><i class="fas fa-check mr-2"></i>Confirmer la réservation</span>';
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>