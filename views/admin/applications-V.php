<?php
require_once __DIR__ . "/../../toolbox/tools.php"; 
$title = "Dossiers";
require __DIR__ . "/../components/head.php"
?>

<body>

<?php require_once __DIR__ . "/../components/header.php"; ?>

<main class="applications">

	<a href="/index.php?page=dashboard" class="btn">← Retour au dashboard</a>

	<?php if (empty($apps)) : ?>

		<p>Aucun dossier pour le moment.</p>

	<?php else : ?>

	<table class="dossiers">
		<thead>
			<tr>
				<th>Date</th>
				<th>Véhicule</th>
				<th>Type</th>
				<th>Demandeur</th>
				<th>Email</th>
				<th>Statut</th>
				<th>Dossier</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($apps as $a) : ?>
			<tr>
				<td><?= e(date('d/m/Y', strtotime($a['created_at']))) ?></td>
				<td><?= e($a['vehicle_name']) ?></td>
				<td><?= e(vehicleType($a['type'])) ?></td>
				<td><?= e($a['name']) ?></td>
				<td><?= e($a['email']) ?></td>
				<td>
					<span class="status status-<?= e($a['status']) ?>">
						<?= e(appStatus($a['status'])) ?>
					</span>
				</td>
				<td>
					<a class="edit-btn" href="/index.php?page=admin_download_dossier&id=<?= (int)$a['id'] ?>" target="_blank">PDF</a>
				</td>
				<td class="actions">
					<form action="/index.php?page=admin_update_application" method="POST">
						<input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
						<input type="hidden" name="status" value="accepted">
						<button type="submit" class="edit-btn">Accepter</button>
					</form>
					<form action="/index.php?page=admin_update_application" method="POST">
						<input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
						<input type="hidden" name="status" value="refused">
						<button type="submit" class="delete-btn">Refuser</button>
					</form>
					<form action="/index.php?page=admin_delete_application" method="POST" onsubmit="return confirm('Supprimer définitivement ce dossier ? Le PDF sera aussi effacé.');">
						<input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
						<button type="submit" class="remove-btn">Supprimer</button>
					</form>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php endif; ?>

</main>

<?php require_once __DIR__ . "/../components/footer.php"; ?>

</body>
</html>