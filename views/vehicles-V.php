<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Véhicules";
require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main>

	<form method="GET" action="/index.php" class="price-filter">

        <?php if ($currentType): ?>
            <input type="hidden" name="type" value="<?= e($currentType) ?>">
        <?php endif; ?>

        <input type="number" name="min" placeholder="Prix min" value="<?= e($min) ?>">
        <input type="number" name="max" placeholder="Prix max" value="<?= e($max) ?>">

        <button type="submit">Filtrer</button>

    </form>

<?php if (empty($vehicles)) : ?>
    <p class="empty">Aucun véhicule disponible.</p>

<?php else : ?>

    <section class="vehicle-grid">
		<p class="filter">
    		Entre 
    		<?= $min ? "Min: $min €" : '' ?>
			 et 
    		<?= $max ? "Max: $max €" : '' ?>
		</p>

        <?php foreach ($vehicles as $vehicle) : 
        
            $image = !empty($vehicle['image']) 
                ? "/uploads/" . e($vehicle['image']) 
                : "/assets/no-photos.png";

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

                    <a class="btn" href="/index.php?page=vehicle&id=<?= (int)$vehicle['id'] ?>">
                        Voir détails
                    </a>

                </div>

            </article>

        <?php endforeach; ?>

    </section>

<?php endif; ?>

</main>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>