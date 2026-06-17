<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Déposer un dossier";

// Récupère un éventuel message d'erreur (puis le consomme)
$error = $_SESSION['apply_error'] ?? null;
$old   = $_SESSION['apply_old'] ?? [];
unset($_SESSION['apply_error'], $_SESSION['apply_old']);

require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main>

<h1>Déposer un dossier</h1>

<form action="/M-Motors/public/index.php?page=submit_app" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="vehicle_id" value="<?= (int)($_GET['vehicle_id'] ?? 0) ?>">

    <label>Nom complet :</label>
    <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" required>

    <label>Email :</label>
    <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required>

    <label>Document (PDF, 5 Mo max) :</label>
    <input type="file" name="document" accept="application/pdf" required>

    <button type="submit">Envoyer</button>

	<?php if ($error) : ?>
		<p class="form-error"><?= e($error) ?></p>
	<?php endif; ?>

</form>

</main>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>
