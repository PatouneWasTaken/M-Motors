<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Véhicules";
require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main>

<?php if (empty($vehicles)) : ?>
    <p class="empty">Aucun véhicule disponible.</p>

<?php else : ?>

    <section class="vehicle-grid">

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
                        <?= e(vehicleType($vehicle['is_for_sale'])) ?>
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