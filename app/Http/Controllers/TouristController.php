<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\Ville;
use Illuminate\Http\Request;
use App\Models\Property;

class TouristController
{
    private function getImageUrl($type, $name) {
        return asset("images/{$type}/" . strtolower(str_replace(' ', '-', $name)) . ".jpg");
    }

    public function index(){
        $properties = Property::all()->map(function($property) {
            $property->image_url = $this->getImageUrl('properties', $property->title);
            return $property;
        });

        $villes = Ville::all()->map(function($ville) {
            $ville->image_url = $this->getImageUrl('cities', $ville->nom);
            return $ville;
        });

        $citiesByCountry = Pays::with('villes')->get()->map(function($pays) {
            $pays->image_url = $this->getImageUrl('countries', $pays->nom);
            $pays->villes = $pays->villes->map(function($ville) {
                $ville->image_url = $this->getImageUrl('cities', $ville->nom);
                return $ville;
            });
            return $pays;
        });

        $data = [
            'property' => $properties,
            'villes' => $villes,
            'citiesByCountry' => $citiesByCountry
        ];
        
        return view("home", compact('data'));
    }
}

