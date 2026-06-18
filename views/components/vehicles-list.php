<?php require_once __DIR__ . "/../../toolbox/tools.php"; ?>

<?php if (empty($vehicles)) : ?>
    <p class="empty">Aucun véhicule trouvé.</p>
<?php else : ?>

    <section class="vehicle-grid">
        <?php foreach ($vehicles as $vehicle) : 
            $image = "/M-Motors/public/assets/no-photos.png";

			if (
    			!empty($vehicle['photo']) &&
    			file_exists(__DIR__ . '/../../uploads/' . $vehicle['photo'])
			) {
    			$image = "/M-Motors/uploads/" . e($vehicle['photo']);
			}
        ?>

        <article class="card">

            <img src="<?= $image ?>" alt="Véhicule" loading="lazy">

            <div class="card-content">

                <h2>
					<?= e($vehicle['brand']) . ' ' .  e($vehicle['model'])?> 
					<span class="type">
						<?= vehicleType($vehicle['type']) === 'Vente' ? 'à vendre' : 'à louer' ?>
					</span>
				</h2>

				<p class="price">
                    <?= number_format($vehicle['price'], 0, ',', ' ') ?> €<?= $vehicle['type'] === 'rent' ? ' /jour' : '' ?>
                </p>

                <p class="description">
                    <?= e(preview($vehicle['description'] ?? '')) ?>
                </p>

            </div>

            <a class="card-cta" href="/M-Motors/public/index.php?page=vehicle&id=<?= (int)$vehicle['id'] ?>">
                <span>Plus d'infos</span>
                <span class="arrow">→</span>
            </a>

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
