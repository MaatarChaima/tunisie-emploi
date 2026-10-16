<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('jobOffers')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'image_url' => 'nullable|string|max:2048',
            'image_file' => 'nullable|image|max:2048',
        ]);

        $imageUrl = $request->image_url;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = $file->getClientOriginalName();
            // Nettoyer un peu le nom de fichier si besoin, mais on garde le nom original
            $path = $file->storeAs('categories', $filename, 'public');
            $imageUrl = Storage::url($path);
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image_url' => $imageUrl,
        ]);

        return back()->with('success', 'Catégorie créée avec succès.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image_url' => 'nullable|string|max:2048',
            'image_file' => 'nullable|image|max:2048',
        ]);

        $imageUrl = $request->image_url;

        // Si un fichier est uploadé, il remplace l'URL saisie
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs('categories', $filename, 'public');
            $imageUrl = Storage::url($path);
        } elseif (!$request->filled('image_url') && !$request->hasFile('image_file')) {
            // Si on ne renseigne ni URL ni fichier, on garde l'ancienne pour éviter d'effacer par erreur, 
            // mais on va laisser tel quel (null si on vide volontairement).
            // Le comportement par défaut ici : mettre à jour avec ce qui est envoyé
        }

        // Si l'utilisateur envoie explicitement une URL, ça écrasera l'ancienne.
        // Si vide, et aucun upload, $imageUrl est null, ce qui supprimera l'image de la catégorie.
        // L'idéal est de proposer une checkbox "Supprimer l'image", ou on accepte null.
        
        // Let's implement keeping old image if new ones are empty to avoid accidental deletion in a quick inline form
        if (!$request->has('image_url') && !$request->hasFile('image_file')) {
            $imageUrl = $category->image_url;
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image_url' => $request->hasFile('image_file') || $request->has('image_url') ? $imageUrl : $category->image_url,
        ]);

        return back()->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(Category $category)
    {
        $offers = $category->jobOffers()->count();
        if ($offers > 0) {
            return back()->with('error', "Impossible de supprimer « {$category->name} » : "
                . $offers . ' offre' . ($offers > 1 ? 's l’utilisent' : ' l’utilise')
                . '. Changez d’abord la catégorie de ces offres.');
        }

        $category->delete();
        return back()->with('success', 'Catégorie supprimée avec succès.');
    }
}
