<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = e($vehicle['name']);
require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main class="app">
<h1>Mes dossiers</h1>

<?php if (empty($applications)) : ?>

    <p>Aucun dossier trouvé.</p>

<?php else : ?>

    <ul>
        <?php foreach ($applications as $app) : ?>
            <li>
                <?= e($app['vehicle_name']) ?> - 
                <?= number_format($app['price'], 0, ',', ' ') ?> € -
                <?= e(vehicleType($vehicle['type'])) ?> -
                Statut : <?= e($appStatus($app['status'])) ?>
            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

</main>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>