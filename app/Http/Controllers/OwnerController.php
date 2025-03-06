<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerController
{
    public function welcome()
    {
        return view('components.owner-welcome');
    }

    public function dashboard()
    {
        // Récupérer toutes les propriétés appartenant à l'utilisateur connecté
        $properties = Property::where('user_id', Auth::id())->get();

        // Récupérer toutes les réservations associées aux propriétés de l'utilisateur
        $bookings = Booking::whereIn('property_id', $properties->pluck('id'))->get();

        // Ajouter des informations supplémentaires à chaque propriété (nombre de réservations et revenu total)
        $properties = $properties->map(function ($property) use ($bookings) {
            $property->booking_count = $bookings->where('property_id', $property->id)->count();
            $property->total_revenue = $bookings->where('property_id', $property->id)->sum('total_price');
            return $property;
        });

        // Calculer les statistiques globales
        $stats = [
            'total_properties' => $properties->count(),
            'active_listings' => $properties->where('is_available', 1)->count(),
            'total_bookings' => $properties->sum('booking_count'),
            'total_revenue' => $properties->sum('total_revenue'),
        ];

        // Retourner la vue avec les données
        return view('owner.dashboard', compact('properties', 'stats'));
    }

    public function become_owner()
    {
        $user = Auth::user();
        $user->update([
            'role' => 'owner'
        ]);

        return redirect()->route('owner.dashboard')
            ->with('success', 'Félicitations ! Vous êtes maintenant un propriétaire sur TouriStay.');
    }

    public function reservations(Request $request)
    {
        $propertyId = $request->query('property_id');
        $owner = auth()->user();
        
        // Get single property with its primary image and bookings
        $property = $owner->properties()
            ->where('id', $propertyId)
            ->with(['primaryImage', 'bookings'])
            ->first(); // Use first() instead of get() for single record
        
        // Check if property exists
        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found or not owned by user',
                'data' => null
            ], 404);
        }
        
        // Structure the response for a single property
        $response = [
            'property' => $property,
            'bookings' => $property->bookings
        ];
        
        // Return JSON response
        return response()->json([
            'success' => true,
            'data' => $response
        ], 200);
    }
}
