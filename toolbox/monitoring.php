<?php

// Surveillance simple de l'application : on journalise les erreurs dans un fichier
// (hors racine web) et on envoie une alerte email en cas d'erreur critique.

// fichier de log, placé dans storage/ (non accessible depuis le web)
define('LOG_FILE', __DIR__ . '/../storage/logs/app.log');

// adresse qui reçoit les alertes (configurable via variable d'environnement)
define('ALERT_EMAIL', getenv('ALERT_EMAIL') ?: 'admin@m-motors.cloud');

// écrit une ligne dans le fichier de log
function logMessage($level, $message) {
    $dir = dirname(LOG_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $line = '[' . date('Y-m-d H:i:s') . "] [$level] " . $message . PHP_EOL;
    error_log($line, 3, LOG_FILE);
}

// envoie une alerte par email (uniquement pour les erreurs critiques)
function sendAlert($subject, $message) {
    $headers = "From: alerte@m-motors.cloud\r\n"
             . "Content-Type: text/plain; charset=utf-8";
    @mail(ALERT_EMAIL, '[M-Motors] ' . $subject, $message, $headers);
}

// les avertissements/notices sont seulement journalisés (sans couper le script)
set_error_handler(function ($severity, $message, $file, $line) {
    logMessage('WARNING', "$message dans $file:$line");
    return false; // PHP continue son traitement normal
});

// une exception non capturée = erreur grave : on journalise, on alerte, on affiche une page propre
set_exception_handler(function ($e) {
    $message = $e->getMessage() . ' dans ' . $e->getFile() . ':' . $e->getLine();
    logMessage('ERROR', $message);
    sendAlert('Erreur critique', $message);

    http_response_code(500);
    if (getenv('APP_ENV') === 'production') {
        echo 'Une erreur est survenue. Merci de réessayer plus tard.';
    } else {
        echo 'Erreur : ' . $message;
    }
});

// capture aussi les erreurs fatales (qui échappent aux gestionnaires ci-dessus)
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        $message = $error['message'] . ' dans ' . $error['file'] . ':' . $error['line'];
        logMessage('FATAL', $message);
        sendAlert('Erreur fatale', $message);
    }
});
