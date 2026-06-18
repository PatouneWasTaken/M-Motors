<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = e($vehicle['brand'] . ' ' . $vehicle['model']);
require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main class="vehicle-detail">

    <?php
        $backQs = http_build_query(array_filter([
            'type'  => $_GET['type']  ?? '',
            'min'   => $_GET['min']   ?? '',
            'max'   => $_GET['max']   ?? '',
            'brand' => $_GET['brand'] ?? '',
        ]));
    ?>
    <a href="/M-Motors/public/index.php<?= $backQs ? '?' . $backQs : '' ?>" class="btn">← Retour au catalogue</a>

    <h1>
        <?= e($vehicle['brand'] . ' ' . $vehicle['model']) ?>
        <span class="type">
            <?= vehicleType($vehicle['type']) === 'Vente' ? 'à vendre' : 'à louer' ?>
        </span>
    </h1>

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

            <p class="price">
                <?= number_format($vehicle['price'], 0, ',', ' ') ?> €<?= $vehicle['type'] === 'rent' ? ' /jour' : '' ?>
            </p>

            <p class="kms">
                <?= number_format($vehicle['kms'], 0, ',', ' ') ?> Kms
            </p>

            <p class="description">
                <?= nl2br(e($vehicle['description'])) ?>
            </p>

			<?php if (isset($_SESSION['user_id'])) : ?>
    		<a class="btn-primary" href="/M-Motors/public/index.php?page=apply&vehicle_id=<?= (int)$vehicle['id'] ?><?= $backQs ? '&' . $backQs : '' ?>">
       			Déposer un dossier
    		</a>
			<?php else : ?>
			<a class="btn-primary" href="/M-Motors/public/index.php?page=login">
        		Connectez-vous pour déposer un dossier
   			 </a>
			<?php endif; ?>

        </div>

    </div>

</main>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>
