<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Connexion / Inscription";

// Récupère d'éventuels messages d'erreur (puis les consomme)
$loginError    = $_SESSION['login_error'] ?? null;
$loginOld      = $_SESSION['login_old'] ?? [];
$registerError = $_SESSION['register_error'] ?? null;
$registerOld   = $_SESSION['register_old'] ?? [];
unset(
    $_SESSION['login_error'], $_SESSION['login_old'],
    $_SESSION['register_error'], $_SESSION['register_old']
);

require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main class="auth">

	<div class="auth-grid">

		<div class="auth-forms">

			<!-- Connexion -->
			<section class="auth-panel login">
				<h2>Connectez-vous</h2>

				<form action="/M-Motors/controllers/login-C.php" method="POST">

    				<label for="login-email">Adresse email :</label>
    				<input type="email" id="login-email" name="email" value="<?= e($loginOld['email'] ?? '') ?>" required>

    				<label for="login-password">Mot de passe :</label>
    				<input type="password" id="login-password" name="password" required>

    				<button type="submit">Se connecter</button>

					<?php if ($loginError) : ?>
						<p class="form-error"><?= e($loginError) ?></p>
					<?php endif; ?>

				</form>
			</section>

			<!-- Inscription -->
			<section class="auth-panel register">
				<h2>Inscrivez-vous</h2>

				<form action="/M-Motors/controllers/register-C.php" method="POST">

    				<label for="reg-firstname">Prénom :</label>
    				<input type="text" id="reg-firstname" name="firstname" value="<?= e($registerOld['firstname'] ?? '') ?>" required>

					<label for="reg-lastname">Nom :</label>
    				<input type="text" id="reg-lastname" name="lastname" value="<?= e($registerOld['lastname'] ?? '') ?>" required>

    				<label for="reg-email">Adresse email :</label>
    				<input type="email" id="reg-email" name="email" value="<?= e($registerOld['email'] ?? '') ?>" required>

    				<label for="reg-password">Mot de passe :</label>
    				<input type="password" id="reg-password" name="password" required>

    				<button type="submit">S'inscrire</button>

					<?php if ($registerError) : ?>
						<p class="form-error"><?= e($registerError) ?></p>
					<?php endif; ?>

				</form>
			</section>

		</div>

		<!-- Tiers droit : logo M-Motors -->
		<section class="auth-brand">
			<img src="/M-Motors/public/assets/letter-m.png" alt="Logo M-Motors">
			<p class="brand-name">M-Motors</p>
		</section>

	</div>

</main>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>