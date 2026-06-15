<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = e($vehicle['brand'] . ' ' . $vehicle['model']);
require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main class="vehicle-detail">

    <h1><?= e($vehicle['brand'] . ' ' . $vehicle['model']) ?></h1>

    <div class="detail-container">

        <div class="detail-image">
            <?php
                $image = "/M-Motors/public/assets/no-photos.png";
                if (
                    !empty($vehicle['photo']) &&
                    file_exists(__DIR__ . '/../uploads/' . $vehicle['photo'])
                ) {
                    $image = "/M-Motors/uploads/" . e($vehicle['photo']);
                }
            ?>
            <img src="<?= $image ?>" alt="<?= e($vehicle['brand'] . ' ' . $vehicle['model']) ?>">
        </div>

        <div class="detail-info">

            <p class="type">
                <?= e(vehicleType($vehicle['type'])) ?>
            </p>

            <p class="price">
                <?= number_format($vehicle['price'], 0, ',', ' ') ?> €
            </p>

            <p class="description">
                <?= nl2br(e($vehicle['description'])) ?>
            </p>

			<?php if (isset($_SESSION['user_id'])) : ?>
    		<a class="submit-btn" href="/M-Motors/public/index.php?page=apply&vehicle_id=<?= (int)$vehicle['id'] ?>">
       			Déposer un dossier
    		</a>
			<?php else : ?>
			<a class="login-btn" href="/M-Motors/public/index.php?page=login">
        		Connectez-vous pour déposer un dossier
   			 </a>
			<?php endif; ?>

        </div>

    </div>

    <a href="/M-Motors/public/index.php" class="btn">← Retour</a>

</main>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>
