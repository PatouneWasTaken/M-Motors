<?php

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function vehicleType($isForSale) {
    return $isForSale ? 'Vente' : 'Location';
}

function preview($text, $length = 100) {
    return strlen($text) > $length 
        ? substr($text, 0, $length) . '...' 
        : $text;
}