<?php require_once __DIR__ . "/../../toolbox/tools.php"; ?>

<section class="admin-vehicles">

<?php foreach ($vehicles as $v): ?>

	<article class="admin-vehicle-row">

    	<p class="name"><?= e($v['brand'] . ' ' . $v['model']) ?></p>

    	<p class="type"><?= e(vehicleType($v['type'])) ?></p>

    	<p class="price"><?= number_format($v['price'], 0, ',', ' ') ?> €</p>

    	<p class="actions">
        	<a href="/M-Motors/public/index.php?page=admin_edit_vehicle&id=<?= (int)$v['id'] ?>">Modifier</a>
        	<a href="/M-Motors/public/index.php?page=admin_delete_vehicle&id=<?= (int)$v['id'] ?>">Supprimer</a>
		</p>

	</article>

<?php endforeach; ?>

</section>
