<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Inscription";

// Récupère un éventuel message d'erreur (puis le consomme)
$error = $_SESSION['register_error'] ?? null;
$old   = $_SESSION['register_old'] ?? [];
unset($_SESSION['register_error'], $_SESSION['register_old']);

require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main class="register">
<h2>Inscription</h2>

<form action="/M-Motors/controllers/register-C.php" method="POST">

    <label for="firstname">Prénom :</label>
    <input type="text" id="firstname" name="firstname" value="<?= e($old['firstname'] ?? '') ?>" required><br><br>

	<label for="lastname">Nom :</label>
    <input type="text" id="lastname" name="lastname" value="<?= e($old['lastname'] ?? '') ?>" required><br><br>

    <label for="email">Adresse email :</label>
    <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" required><br><br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">S'inscrire</button>

	<?php if ($error) : ?>
		<p class="form-error"><?= e($error) ?></p>
	<?php endif; ?>

</form>
</main>

</body>
</html>
