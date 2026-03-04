<?php

namespace App\Helpers;

class CategoryHelper
{
    /**
     * Retourne l'icône FontAwesome appropriée pour une catégorie
     */
    public static function getIcon(string $categoryName): string
    {
        // Normaliser le nom de la catégorie (minuscules, sans accents)
        $normalizedName = strtolower(trim($categoryName));
        
        // Mapping des catégories vers leurs icônes
        $categoryIcons = [
            // Électronique et technologie
            'électronique' => 'fas fa-laptop',
            'electronique' => 'fas fa-laptop',
            'informatique' => 'fas fa-desktop',
            'ordinateur' => 'fas fa-desktop',
            'téléphone' => 'fas fa-mobile-alt',
            'telephone' => 'fas fa-mobile-alt',
            'smartphone' => 'fas fa-mobile-alt',
            'accessoires' => 'fas fa-headphones',
            'gaming' => 'fas fa-gamepad',
            'jeux vidéo' => 'fas fa-gamepad',
            'photo' => 'fas fa-camera',
            'photographie' => 'fas fa-camera',
            'tv' => 'fas fa-tv',
            'télévision' => 'fas fa-tv',
            
            // Mode et vêtements
            'mode' => 'fas fa-tshirt',
            'vêtements' => 'fas fa-tshirt',
            'vetements' => 'fas fa-tshirt',
            'chaussures' => 'fas fa-shoe-prints',
            'sacs' => 'fas fa-shopping-bag',
            'montres' => 'fas fa-clock',
            'bijoux' => 'fas fa-gem',
            'lunettes' => 'fas fa-glasses',
            'beauté' => 'fas fa-spa',
            'soins' => 'fas fa-spa',
            'cosmétiques' => 'fas fa-spray-can',
            'maquillage' => 'fas fa-palette',
            
            // Maison et jardin
            'maison' => 'fas fa-home',
            'meubles' => 'fas fa-couch',
            'décoration' => 'fas fa-paint-brush',
            'decoration' => 'fas fa-paint-brush',
            'cuisine' => 'fas fa-utensils',
            'jardin' => 'fas fa-seedling',
            'bricolage' => 'fas fa-tools',
            'électroménager' => 'fas fa-blender',
            'electromenager' => 'fas fa-blender',
            'linge' => 'fas fa-tshirt',
            'literie' => 'fas fa-bed',
            
            // Sports et loisirs
            'sports' => 'fas fa-football-ball',
            'sport' => 'fas fa-football-ball',
            'fitness' => 'fas fa-dumbbell',
            'musculation' => 'fas fa-dumbbell',
            'vélo' => 'fas fa-bicycle',
            'velo' => 'fas fa-bicycle',
            'natation' => 'fas fa-swimmer',
            'course' => 'fas fa-running',
            'randonnée' => 'fas fa-hiking',
            'camping' => 'fas fa-campground',
            
            // Livres et papeterie
            'livres' => 'fas fa-book',
            'livre' => 'fas fa-book',
            'papeterie' => 'fas fa-pen',
            'bureau' => 'fas fa-pen',
            'magazines' => 'fas fa-newspaper',
            'bande dessinée' => 'fas fa-book-open',
            'bd' => 'fas fa-book-open',
            
            // Jouets et enfants
            'jouets' => 'fas fa-gamepad',
            'jouet' => 'fas fa-gamepad',
            'enfants' => 'fas fa-child',
            'bébé' => 'fas fa-baby',
            'puériculture' => 'fas fa-baby',
            'poupées' => 'fas fa-user-friends',
            
            // Alimentation et boissons
            'alimentation' => 'fas fa-utensils',
            'complement alimentaire' => 'fas fa-pills',
            'complements alimentaires' => 'fas fa-pills',
            'complément alimentaire' => 'fas fa-pills',
            'compléments alimentaires' => 'fas fa-pills',
            'nutrition' => 'fas fa-apple-alt',
            'parapharmacie' => 'fas fa-prescription-bottle-alt',
            'épicerie' => 'fas fa-shopping-basket',
            'boissons' => 'fas fa-wine-glass',
            'vin' => 'fas fa-wine-bottle',
            'bière' => 'fas fa-beer',
            'café' => 'fas fa-coffee',
            'thé' => 'fas fa-mug-hot',
            'chocolat' => 'fas fa-cookie',
            'confiseries' => 'fas fa-candy-cane',
            
            // Santé et bien-être
            'santé' => 'fas fa-heartbeat',
            'sante' => 'fas fa-heartbeat',
            'médecine' => 'fas fa-medkit',
            'medecine' => 'fas fa-medkit',
            'pharmacie' => 'fas fa-pills',
            'bien-être' => 'fas fa-spa',
            'bien etre' => 'fas fa-spa',
            'relaxation' => 'fas fa-spa',
            
            // Automobile et transport
            'auto' => 'fas fa-car',
            'automobile' => 'fas fa-car',
            'moto' => 'fas fa-motorcycle',
            'véhicules' => 'fas fa-car',
            'pièces' => 'fas fa-cogs',
            'accessoires auto' => 'fas fa-key',
            
            // Animaux
            'animaux' => 'fas fa-paw',
            'chien' => 'fas fa-dog',
            'chat' => 'fas fa-cat',
            'animaux domestiques' => 'fas fa-home',
            
            // Musique et instruments
            'musique' => 'fas fa-music',
            'instruments' => 'fas fa-guitar',
            'guitare' => 'fas fa-guitar',
            'piano' => 'fas fa-music',
            'hifi' => 'fas fa-headphones',
            
            // Voyages
            'voyages' => 'fas fa-plane',
            'bagage' => 'fas fa-suitcase',
            'valise' => 'fas fa-suitcase',
            
            // Services et autres
            'services' => 'fas fa-concierge-bell',
            'offres' => 'fas fa-gift',
            'promotions' => 'fas fa-tag',
            'soldes' => 'fas fa-percentage',
            'nouveautés' => 'fas fa-sparkles',
            'divers' => 'fas fa-box',
            'autre' => 'fas fa-box',
            'autres' => 'fas fa-box',
        ];
        
        // Recherche exacte d'abord
        if (isset($categoryIcons[$normalizedName])) {
            return $categoryIcons[$normalizedName];
        }
        
        // Recherche partielle (contient)
        foreach ($categoryIcons as $key => $icon) {
            if (strpos($normalizedName, $key) !== false || strpos($key, $normalizedName) !== false) {
                return $icon;
            }
        }
        
        // Icône par défaut selon des mots-clés
        if (preg_match('/(homme|femme|garçon|fille|adulte|enfant)/', $normalizedName)) {
            return 'fas fa-user';
        }
        
        if (preg_match('/(fleur|plante|nature)/', $normalizedName)) {
            return 'fas fa-leaf';
        }
        
        if (preg_match('/(cœur|amour|rose)/', $normalizedName)) {
            return 'fas fa-heart';
        }
        
        if (preg_match('/(étoile|star)/', $normalizedName)) {
            return 'fas fa-star';
        }
        
        // Icône par défaut
        return 'fas fa-tag';
    }
}
