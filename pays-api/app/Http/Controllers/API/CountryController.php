<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    // Liste de tous les pays
    public function index()
    {
        $countries = Country::all();
        return response()->json(['countries' => $countries], 200);
    }

    // Détails d'un pays spécifique
    public function show($id)
    {
        $country = Country::find($id);
        
        if (!$country) {
            return response()->json(['message' => 'Pays non trouvé'], 404);
        }
        
        return response()->json(['country' => $country], 200);
    }

    // Création d'un nouveau pays
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'capital' => 'required|string|max:255',
            'population' => 'required|integer',
            'region' => 'required|string|max:255',
            'flag' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'language' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:255',
            'motto' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Gestion de l'image du drapeau
        $flagUrl = null;
        if ($request->hasFile('flag')) {
            $flagPath = $request->file('flag')->store('flags', 'public');
            $flagUrl = Storage::url($flagPath);
        } elseif ($request->filled('flag_url')) {
            $flagUrl = $request->flag_url;
        }

        // Création du pays
        $country = Country::create([
            'name' => $request->name,
            'capital' => $request->capital,
            'population' => $request->population,
            'region' => $request->region,
            'flag_url' => $flagUrl,
            'language' => $request->language,
            'currency' => $request->currency,
            'motto' => $request->motto,
        ]);

        return response()->json(['message' => 'Pays créé avec succès', 'country' => $country], 201);
    }

    // Mise à jour d'un pays
    public function update(Request $request, $id)
    {
        // Recherche du pays
        $country = Country::find($id);
        
        if (!$country) {
            return response()->json(['message' => 'Pays non trouvé'], 404);
        }

        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'capital' => 'nullable|string|max:255',
            'population' => 'nullable|integer',
            'region' => 'nullable|string|max:255',
            'flag' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'language' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:255',
            'motto' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Gestion de l'image du drapeau
        if ($request->hasFile('flag')) {
            // Supprimer l'ancien drapeau si nécessaire
            if ($country->flag_url && Storage::disk('public')->exists(str_replace('/storage/', '', $country->flag_url))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $country->flag_url));
            }
            
            $flagPath = $request->file('flag')->store('flags', 'public');
            $country->flag_url = Storage::url($flagPath);
        } elseif ($request->filled('flag_url')) {
            $country->flag_url = $request->flag_url;
        }

        // Mise à jour des champs
        if ($request->filled('name')) {
            $country->name = $request->name;
        }
        
        if ($request->filled('capital')) {
            $country->capital = $request->capital;
        }
        
        if ($request->filled('population')) {
            $country->population = $request->population;
        }
        
        if ($request->filled('region')) {
            $country->region = $request->region;
        }
        
        if ($request->filled('language')) {
            $country->language = $request->language;
        }
        
        if ($request->filled('currency')) {
            $country->currency = $request->currency;
        }
        
        if ($request->filled('motto')) {
            $country->motto = $request->motto;
        }

        $country->save();

        return response()->json(['message' => 'Pays mis à jour avec succès', 'country' => $country], 200);
    }

    // Suppression d'un pays
    public function destroy($id)
    {
        $country = Country::find($id);
        
        if (!$country) {
            return response()->json(['message' => 'Pays non trouvé'], 404);
        }

        // Suppression de l'image du drapeau si nécessaire
        if ($country->flag_url && Storage::disk('public')->exists(str_replace('/storage/', '', $country->flag_url))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $country->flag_url));
        }

        $country->delete();

        return response()->json(['message' => 'Pays supprimé avec succès'], 200);
    }

    // Upload ou mise à jour d'un drapeau (endpoint séparé)
    public function updateFlag(Request $request, $id)
    {
        // Recherche du pays
        $country = Country::find($id);
        
        if (!$country) {
            return response()->json(['message' => 'Pays non trouvé'], 404);
        }

        // Validation de l'image
        $validator = Validator::make($request->all(), [
            'flag' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Supprimer l'ancien drapeau si nécessaire
        if ($country->flag_url && Storage::disk('public')->exists(str_replace('/storage/', '', $country->flag_url))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $country->flag_url));
        }

        // Enregistrer le nouveau drapeau
        $flagPath = $request->file('flag')->store('flags', 'public');
        $country->flag_url = Storage::url($flagPath);
        $country->save();

        return response()->json([
            'message' => 'Drapeau mis à jour avec succès',
            'flag_url' => $country->flag_url
        ], 200);
    }

    // Récupération du drapeau
    public function getFlag($id)
    {
        $country = Country::find($id);
        
        if (!$country) {
            return response()->json(['message' => 'Pays non trouvé'], 404);
        }

        if (!$country->flag_url) {
            return response()->json(['message' => 'Aucun drapeau disponible pour ce pays'], 404);
        }

        return response()->json(['flag_url' => $country->flag_url], 200);
    }
}