<?php require_once __DIR__ . "/../../toolbox/tools.php"; ?>

<section class="admin-vehicles">

<?php foreach ($vehicles as $v):

	$image = "/M-Motors/public/assets/no-photos.png";

	if (
		!empty($v['photo']) &&
		file_exists(__DIR__ . '/../../uploads/' . $v['photo'])
	) {
		$image = "/M-Motors/uploads/" . e($v['photo']);
	}
?>

	<article class="admin-vehicle-row">

		<img class="thumb" src="<?= $image ?>" alt="" loading="lazy">

    	<div class="info">
        	<p class="name"><?= e($v['brand'] . ' ' . $v['model']) ?></p>
        	<p class="description"><?= e(preview($v['description'] ?? '')) ?></p>
    	</div>

    	<p class="kms"><?= number_format($v['kms'], 0, ',', ' ') ?> Kms</p>

    	<p class="type"><?= e(vehicleType($v['type'])) ?></p>

    	<p class="price">
        	<?= number_format($v['price'], 0, ',', ' ') ?> €<?= $v['type'] === 'rent' ? ' /jour' : '' ?>
    	</p>

    	<p class="actions">
        	<a class="edit-btn" href="/M-Motors/public/index.php?page=admin_edit_vehicle&id=<?= (int)$v['id'] ?>">Modifier</a>

        	<form action="/M-Motors/public/index.php?page=admin_delete_vehicle" method="POST" onsubmit="return confirm('Supprimer ce véhicule ? Les dossiers liés seront aussi supprimés.');">
            	<input type="hidden" name="id" value="<?= (int)$v['id'] ?>">
            	<button type="submit" class="delete-btn">Supprimer</button>
        	</form>
		</p>

	</article>

<?php endforeach; ?>

</section>
