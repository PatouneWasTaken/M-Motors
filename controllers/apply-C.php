<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../toolbox/validators.php';

class ApplicationController {

    // Taille maximale autorisée pour le dossier PDF : 5 Mo
    private const MAX_SIZE = 5 * 1024 * 1024;

    // En cas d'erreur : mémorise le message + les champs saisis, et revient au formulaire
    private function fail($message, $vehicleId, $old = []) {
        $_SESSION['apply_error'] = $message;
        $_SESSION['apply_old'] = $old;
        header("Location: /index.php?page=apply&vehicle_id=" . (int)$vehicleId);
        exit;
    }

    public function store() {

        if (!isset($_SESSION['user_id'])) {
            header("Location: /index.php?page=login");
            exit;
        }

        // On n'accepte que l'envoi par POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die("Méthode non autorisée");
        }

        global $pdo;

        $vehicle_id = (int) ($_POST['vehicle_id'] ?? 0);
        $old = [
            'name'  => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
        ];

        // --- Validation des champs texte ---
        if (validateApplication($_POST)) {
            $this->fail("Formulaire invalide", $vehicle_id, $old);
        }

        $user_id = (int) $_SESSION['user_id'];
        $name    = trim($_POST['name']);
        $email   = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

        // Le véhicule doit exister
        $stmt = $pdo->prepare("SELECT id FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicle_id]);
        if (!$stmt->fetch()) {
            $this->fail("Véhicule introuvable", $vehicle_id, $old);
        }

        // --- Vérification du fichier ---
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $this->fail("Aucun fichier reçu ou erreur lors de l'envoi", $vehicle_id, $old);
        }

        $file = $_FILES['document'];

        // Taille (entre 1 octet et 5 Mo)
        if (!isWithinMaxSize($file['size'], self::MAX_SIZE)) {
            $this->fail("Le fichier ne doit pas dépasser 5 Mo", $vehicle_id, $old);
        }

        // Type réel du contenu (on ne se fie pas à l'extension envoyée)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);

        if (!isPdfMimeType($mime)) {
            $this->fail("Seuls les fichiers PDF sont acceptés", $vehicle_id, $old);
        }

        // Nom de fichier sûr et imprévisible (jamais le nom d'origine)
        $filename = 'dossier_' . $user_id . '_' . bin2hex(random_bytes(8)) . '.pdf';

        // Stockage HORS du dossier public, dans un répertoire protégé
        $dir = __DIR__ . '/../storage/dossiers/';
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            $this->fail("Impossible de préparer le stockage", $vehicle_id, $old);
        }

        $target = $dir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            $this->fail("Échec de l'enregistrement du fichier", $vehicle_id, $old);
        }

        // --- Enregistrement en base ---
        $sql = "INSERT INTO applications
                (user_id, vehicle_id, name, email, document, status)
                VALUES (:user_id, :vehicle_id, :name, :email, :document, 'pending')";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user_id'    => $user_id,
            'vehicle_id' => $vehicle_id,
            'name'       => $name,
            'email'      => $email,
            'document'   => $filename,
        ]);

        header("Location: /index.php?success=1");
        exit;
    }
}
