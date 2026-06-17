<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Mon compte";

$error   = $_SESSION['account_error'] ?? null;
$success = $_SESSION['account_success'] ?? null;
unset($_SESSION['account_error'], $_SESSION['account_success']);

require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main class="account">

	<div class="auth-grid">

		<div class="account-left">

			<?php if ($error) : ?>
				<p class="form-error"><?= e($error) ?></p>
			<?php endif; ?>

			<?php if ($success) : ?>
				<p class="form-success"><?= e($success) ?></p>
			<?php endif; ?>

			<div class="auth-forms">

				<!-- Informations personnelles -->
				<section class="auth-panel">
					<h2>Mes informations</h2>

					<form action="/M-Motors/public/index.php?page=account_update_profile" method="POST">
						<label for="acc-name">Nom :</label>
						<input type="text" id="acc-name" name="name" value="<?= e($user['name'] ?? '') ?>" required>

						<label for="acc-email">Email :</label>
						<input type="email" id="acc-email" name="email" value="<?= e($user['email'] ?? '') ?>" required>

						<button type="submit">Enregistrer</button>
					</form>
				</section>

				<!-- Mot de passe -->
				<section class="auth-panel">
					<h2>Changer de mot de passe</h2>

					<form action="/M-Motors/public/index.php?page=account_update_password" method="POST">
						<label for="acc-current">Mot de passe actuel :</label>
						<input type="password" id="acc-current" name="current_password" required>

						<label for="acc-new">Nouveau mot de passe :</label>
						<input type="password" id="acc-new" name="new_password" required>

						<button type="submit">Modifier</button>
					</form>
				</section>

			</div>

			<!-- Dossiers -->
			<section class="account-applications">
				<h2>Mes dossiers</h2>

				<?php if (empty($applications)) : ?>
					<p>Aucun dossier déposé pour le moment.</p>
				<?php else : ?>
					<ul>
						<?php foreach ($applications as $app) : ?>
							<li>
								<?= e($app['vehicle_name']) ?> —
								<?= number_format($app['price'], 0, ',', ' ') ?> € —
								<?= e(vehicleType($app['type'])) ?> —
								Statut : <span class="status status-<?= e($app['status']) ?>"><?= e(appStatus($app['status'])) ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</section>

			<!-- Déconnexion -->
			<form action="/M-Motors/public/index.php?page=logout" method="POST" class="logout-form">
				<button type="submit">Se déconnecter</button>
			</form>

		</div>

		<!-- Tiers droit : logo M-Motors, pleine hauteur -->
		<section class="auth-brand">
			<img src="/M-Motors/public/assets/letter-m.png" alt="Logo M-Motors">
			<p class="brand-name">M-Motors</p>
		</section>

	</div>

</main>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>