<?php

// Petites fonctions utilitaires utilisées un peu partout dans les vues.

// échappe le HTML pour éviter les injections quand on affiche du texte
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// coupe un texte trop long et ajoute "..." à la fin
function preview($text, $length = 100) {
    return strlen($text) > $length
        ? substr($text, 0, $length) . '...'
        : $text;
}

// traduit le type de véhicule en français pour l'affichage
function vehicleType($type) {
    return $type === 'sale' ? 'Vente' : 'Location';
}

// traduit le statut d'un dossier en français
function appStatus($status) {
    return match($status) {
        'pending' => 'En attente',
        'accepted' => 'Accepté',
        'refused' => 'Refusé',
        default => $status
    };
}
