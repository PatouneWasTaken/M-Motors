<?php

$page = $_GET['page'] ?? 'home';

if ($page === 'register') {
    require __DIR__ . '/../views/register-form.php';
}