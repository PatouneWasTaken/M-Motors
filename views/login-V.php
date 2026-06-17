<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Connexion";

// Récupère un éventuel message d'erreur (puis le consomme)
$error = $_SESSION['login_error'] ?? null;
$old   = $_SESSION['login_old'] ?? [];
unset($_SESSION['login_error'], $_SESSION['login_old']);

require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main class="login">
<h2>Connexion</h2>

<form action="/M-Motors/controllers/login-C.php" method="POST">

    <label for="email">Adresse email :</label>
    <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" required><br><br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>

	<?php if ($error) : ?>
		<p class="form-error"><?= e($error) ?></p>
	<?php endif; ?>

</form>
</main>

</body>
</html>
