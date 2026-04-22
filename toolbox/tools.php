<?php

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function preview($text, $length = 100) {
    return strlen($text) > $length 
        ? substr($text, 0, $length) . '...' 
        : $text;
}

function vehicleType($type) {
    return $type === 'sale' ? 'Vente' : 'Location';
}

function appStatus($status) {
    return match($status) {
        'pending' => 'En attente',
        'accepted' => 'Accepté',
        'refused' => 'Refusé',
        default => $status
    };
}