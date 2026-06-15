<?php

// Peut être appelé directement OU via le routeur (qui démarre déjà la session)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

header("Location: /M-Motors/public/index.php?page=login");
exit;
