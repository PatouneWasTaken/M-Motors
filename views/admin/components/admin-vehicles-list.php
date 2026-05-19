<?php require_once __DIR__ . "/../../toolbox/tools.php"; ?>

<?php if (empty($vehicles)) : ?>
    <p class="empty">Aucun véhicule trouvé.</p>
<?php else : ?>

    <section class="vehicle-grid">
        <?php foreach ($vehicles as $vehicle) : 
            $image = !empty($vehicle['image']) 
                ? "/uploads/" . e($vehicle['image']) 
                : "/assets/no-image.png";
        ?>

        <article class="card">

            <img src="<?= $image ?>" alt="Véhicule" loading="lazy">

            <div class="card-content">

                <h2><?= e($vehicle['name']) ?></h2>

                <p class="type">
                    <?= e(vehicleType($vehicle['type'])) ?>
                </p>

                <p class="description">
                    <?= e(preview($vehicle['description'] ?? '')) ?>
                </p>

                <p class="price">
                    <?= number_format($vehicle['price'], 0, ',', ' ') ?> €
                </p>

                <a href="/index.php?page=admin_edit_vehicle&id=<?= $v['id'] ?>">
					Editer
				</a>

				<a href="/index.php?page=admin_delete_vehicle&id=<?= $v['id'] ?>">
					Supprimer
				</a>

            </div>
        </article>

        <?php endforeach; ?>

    </section>

	<?php if ($totalPages > 1) : ?>
	<div class="pagination">
    	<?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <button class="page-btn" data-page="<?= $i ?>">
            <?= $i ?>
        </button>
    <?php endfor; ?>
	</div>
	<?php endif; ?>

<?php endif; ?>