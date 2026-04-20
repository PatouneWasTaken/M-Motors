<?php require_once __DIR__ . "/../toolbox/tools.php"; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Véhicules</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

<?php require_once __DIR__ . "/header-V.php"; ?>

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

                    <a class="btn" href="/vehicle.php?id=<?= (int)$vehicle['id'] ?>">
                        Voir détails
                    </a>

                </div>

            </article>

        <?php endforeach; ?>

    </section>

<?php endif; ?>

</main>

<?php require_once __DIR__ . "/footer-V.php"; ?>

</body>
</html>