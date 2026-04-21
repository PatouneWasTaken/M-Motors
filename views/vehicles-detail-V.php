<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = e($vehicle['name']);
require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main class="vehicle-detail">

    <h1><?= e($vehicle['name']) ?></h1>

    <div class="detail-container">

        <div class="detail-image">
            <img 
                src="<?= !empty($vehicle['image']) ? '/uploads/' . e($vehicle['image']) : '/assets/no-image.png' ?>" 
                alt="Véhicule"
            >
        </div>

        <div class="detail-info">

            <p class="type">
                <?= e(vehicleType($vehicle['is_for_sale'])) ?>
            </p>

            <p class="price">
                <?= number_format($vehicle['price'], 0, ',', ' ') ?> €
            </p>

            <p class="description">
                <?= nl2br(e($vehicle['description'])) ?>
            </p>

        </div>

    </div>

    <a href="/index.php" class="btn">← Retour</a>

</main>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>