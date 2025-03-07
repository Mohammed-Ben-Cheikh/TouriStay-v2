<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\Pays;
use App\Models\User;
use App\Models\Booking;
use App\Models\Property;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Stripe\Exception\ApiErrorException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController
{
    public function index($id, Request $request)
    {
        $user = Auth::user();
        $property = Property::findOrFail($id);
        $booking = Booking::where('property_id', $property->id)
            ->orderBy('check_out', 'desc')
            ->first();
        return view('Hébergement.check-in', compact('property', 'user'));
    }

    public function store(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vous devez être connecté pour effectuer une réservation'
                ], 401);
            }

            $userId = Auth::id();

            // Validate the request
            $validated = $request->validate([
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'full_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:20',
                'property_id' => 'required|exists:properties,id',
                'guests' => 'required|integer|min:1'
            ]);

            // Convertir les dates en Carbon pour une meilleure manipulation
            $checkIn = Carbon::parse($validated['check_in']);
            $checkOut = Carbon::parse($validated['check_out']);

            // Vérification améliorée des chevauchements de dates
            $existingBooking = Booking::where('property_id', $validated['property_id'])
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->where(function ($q) use ($checkIn, $checkOut) {
                        // Vérifie si une réservation existe dans la période demandée
                        $q->whereBetween('check_in', [$checkIn, $checkOut])
                            ->orWhereBetween('check_out', [$checkIn, $checkOut])
                            // Vérifie si la période demandée englobe une réservation existante
                            ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in', '<=', $checkIn)
                                ->where('check_out', '>=', $checkOut);
                        });
                    });
                })
                ->first();

            if ($existingBooking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'La période du ' . $checkIn->format('d/m/Y') . ' au ' . $checkOut->format('d/m/Y') . ' n\'est pas disponible'
                ], 422);
            }

            $property = Property::findOrFail($validated['property_id']);

            if ($validated['guests'] > $property->max_guests) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Le nombre de voyageurs dépasse la capacité maximale'
                ], 422);
            }

            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $nights * $property->price * $validated['guests'];

            $booking = Booking::create([
                'user_id' => $userId, // Use the authenticated user's ID
                'property_id' => $validated['property_id'],
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'guests' => $validated['guests'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'guest_name' => $validated['full_name'],
                'guest_email' => $validated['email'],
                'guest_phone' => $validated['phone']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Réservation confirmée avec succès',
                'booking' => $booking,
                'id' => $booking->id
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de la réservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getReservations(Request $request)
    {
        try {
            // Récupérer l'ID de la propriété depuis la requête
            $propertyId = $request->query('property_id');

            // Validation de l'ID de la propriété
            if (!$propertyId) {
                return response()->json(['error' => 'Property ID is required'], 400);
            }

            // Récupérer la propriété ou échouer avec 404
            $property = Property::findOrFail($propertyId);

            // Récupérer le paramètre 'check' pour déterminer la réponse
            $check = $request->query('check');

            if ($check === 'availability') {
                return response()->json([
                    'available_until' => $property->available_until ? $property->available_until->format('Y-m-d') : null,
                ], 200);
            } elseif ($check === 'bookingData') {
                // Récupérer les réservations confirmées
                $bookings = Booking::where('property_id', $propertyId)
                    ->where('status', 'confirmed')
                    ->get();

                // Transformer les réservations en événements
                $events = $bookings->map(function ($booking) use ($property) {
                    return [
                        'id' => $booking->id,
                        'title' => 'Réservé', // Option : $booking->guest_name ? "Réservé par {$booking->guest_name}" : 'Réservé'
                        'start' => $booking->check_in->format('Y-m-d'),
                        'end' => $booking->check_out->format('Y-m-d'),
                        'backgroundColor' => '#EF4444',
                        'borderColor' => '#DC2626',
                        'display' => 'block',
                        'overlap' => false,
                        'available_until' => $property->available_until ? $property->available_until->format('Y-m-d') : null,
                    ];
                });

                return response()->json($events, 200);
            }

            // Si 'check' n'est ni 'availability' ni 'bookingData'
            return response()->json(['error' => 'Invalid check parameter. Use "availability" or "bookingData"'], 400);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Property not found',
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching reservations',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getBooking(Request $request)
    {
        try {
            $bookingId = $request->query('booking_id');
            $booking = Booking::findOrFail($bookingId);
            return response()->json($booking);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching booking',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function reservationIndex(Request $request)
    {
        return view('Hébergement.payment');
    }

    public function processPayment(Request $request)
    {
        try {
            // Validation des données d'entrée
            $validated = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'payment_method_id' => 'required|string'
            ]);

            // Récupération de la réservation
            $booking = Booking::findOrFail($validated['booking_id']);

            // Vérification si la réservation est déjà payée
            if ($booking->status === 'confirmed') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cette réservation a déjà été payée.'
                ], 400);
            }

            // Vérification du montant valide
            if ($booking->total_price <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Le montant de la réservation est invalide.'
                ], 400);
            }

            // Configuration de Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            // Création du PaymentIntent
            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => (int) ($booking->total_price * 100), // Conversion en centimes
                    'currency' => 'eur',
                    'payment_method' => $validated['payment_method_id'],
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'return_url' => route('booking.confirmation', $booking->id), // Solution 2 (optionnelle)
                    // 'automatic_payment_methods' => [
                    //     'enabled' => true,
                    //     'allow_redirects' => 'never' // Solution 1 : empêche les redirections
                    // ],
                    'metadata' => [
                        'booking_id' => $booking->id,
                        'guest_name' => $booking->guest_name,
                        'property_id' => $booking->property_id
                    ]
                ]);
            } catch (ApiErrorException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erreur de paiement : ' . $e->getMessage()
                ], 400);
            }

            // Gestion des statuts du paiement
            if ($paymentIntent->status === 'succeeded') {
                // Mise à jour de la réservation comme payée
                $booking->update([
                    'status' => 'confirmed',
                    'payment_id' => $paymentIntent->id,
                    'paid_at' => now()
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Paiement effectué avec succès.'
                ]);
            } elseif ($paymentIntent->status === 'requires_action') {
                return response()->json([
                    'status' => 'requires_action',
                    'message' => 'L’authentification supplémentaire est requise.',
                    'payment_intent_client_secret' => $paymentIntent->client_secret
                ]);
            } elseif ($paymentIntent->status === 'requires_payment_method') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Le paiement a échoué, veuillez essayer un autre moyen de paiement.'
                ], 400);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Le paiement a échoué. Statut: ' . $paymentIntent->status
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur inattendue est survenue: ' . $e->getMessage()
            ], 500);
        }
    }

    public function réservationIndex()
    {
        $reservations = Booking::where('user_id', Auth::id())->get();
        // dd($reservations);
        return view('réservation.index', compact('reservations'));
    }

    public function showConfirmation($id)
    {
        return view('Hébergement.confirmation');
    }

    public function confirmationData($id)
    {
        $user = Auth::user();
        $reservation = Booking::findOrFail($id);
        $property = Property::with('primaryImage')->findOrFail($reservation->property_id);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => auth()->user()->profile_photo_url // Adjust if your User model has a different field
                ],
                'reservation' => $reservation,
                'property' => $property
            ]
        ]);
    }

    public function downloadPdf($id)
    {
        $booking = Booking::findOrFail($id);
        $property = Property::with('primaryImage')->findOrFail($booking->property_id);
        $user = User::findOrFail($booking->user_id);

        $data = [
            'booking' => $booking,
            'property' => $property,
            'user' => $user,
            'date' => Carbon::now()->format('d/m/Y')
        ];
        // dd($data);

        $pdf = PDF::loadView('pdf.booking', $data);

        return $pdf->download('reservation-' . $booking->id . '.pdf');
    }
}