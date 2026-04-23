<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Véhicules";
require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main>

	<?php
		$min = $_GET['min'] ?? '';
		$max = $_GET['max'] ?? '';
		$currentBrand = $_GET['brand'] ?? '';
	?>

	<form class="filters" id="filters">

        <?php if ($currentType): ?>
            <input type="hidden" name="type" value="<?= e($currentType) ?>">
        <?php endif; ?>

        <input type="number" name="min" placeholder="Prix min" value="<?= e($min) ?>">
        <input type="number" name="max" placeholder="Prix max" value="<?= e($max) ?>">

		<select name="brand">
        	<option value="">Toutes marques</option>
        	<?php foreach ($brands as $b) : ?>
            <option value="<?= e($b) ?>"
                <?= $currentBrand === $b ? 'selected' : '' ?>>
                <?= e($b) ?>
            </option>
        	<?php endforeach; ?>
    	</select>

        <button type="submit">Filtrer</button>

    </form>

	<div class="fade" id="vehicles-container"></div>

</main>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>