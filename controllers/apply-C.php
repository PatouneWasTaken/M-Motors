<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../toolbox/validators.php';

class ApplicationController {

    // Taille maximale autorisée pour le dossier PDF : 5 Mo
    private const MAX_SIZE = 5 * 1024 * 1024;

    public function store() {

        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            die("Accès refusé");
        }

        // On n'accepte que l'envoi par POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die("Méthode non autorisée");
        }

        global $pdo;

        // --- Validation des champs texte ---
        if (validateApplication($_POST)) {
            die("Formulaire invalide");
        }

        $user_id    = (int) $_SESSION['user_id'];
        $vehicle_id = (int) $_POST['vehicle_id'];
        $name       = trim($_POST['name']);
        $email      = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

        // Le véhicule doit exister
        $stmt = $pdo->prepare("SELECT id FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicle_id]);
        if (!$stmt->fetch()) {
            die("Véhicule introuvable");
        }

        // --- Vérification du fichier ---
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            die("Aucun fichier reçu ou erreur lors de l'envoi");
        }

        $file = $_FILES['document'];

        // Taille (entre 1 octet et 5 Mo)
        if (!isWithinMaxSize($file['size'], self::MAX_SIZE)) {
            die("Le fichier ne doit pas dépasser 5 Mo");
        }

        // Type réel du contenu (on ne se fie pas à l'extension envoyée)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);

        if (!isPdfMimeType($mime)) {
            die("Seuls les fichiers PDF sont acceptés");
        }

        // Nom de fichier sûr et imprévisible (jamais le nom d'origine)
        $filename = 'dossier_' . $user_id . '_' . bin2hex(random_bytes(8)) . '.pdf';

        // Stockage HORS du dossier public, dans un répertoire protégé
        $dir = __DIR__ . '/../storage/dossiers/';
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            die("Impossible de préparer le stockage");
        }

        $target = $dir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            die("Échec de l'enregistrement du fichier");
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

        header("Location: /M-Motors/public/index.php?success=1");
        exit;
    }
}
