<div>
	<?php
		$min = $_GET['min'] ?? '';
		$max = $_GET['max'] ?? '';
		$currentBrand = $_GET['brand'] ?? '';
	?>

	<form class="filters" id="filters">

        <?php if (!empty($showTypeFilter)): ?>
            <select name="type">
                <option value="">Tous</option>
                <option value="sale" <?= ($_GET['type'] ?? '') === 'sale' ? 'selected' : '' ?>>Vente</option>
                <option value="rent" <?= ($_GET['type'] ?? '') === 'rent' ? 'selected' : '' ?>>Location</option>
            </select>
        <?php elseif ($currentType): ?>
            <input type="hidden" name="type" value="<?= e($currentType) ?>">
        <?php endif; ?>

		<select name="brand">
        	<option value="">Toutes marques</option>
        	<?php foreach ($brands as $b) : ?>
            <option value="<?= e($b) ?>"
                <?= $currentBrand === $b ? 'selected' : '' ?>>
                <?= e($b) ?>
            </option>
        	<?php endforeach; ?>
    	</select>

        <input type="number" name="min" placeholder="Prix min" value="<?= e($min) ?>">
        <input type="number" name="max" placeholder="Prix max" value="<?= e($max) ?>">

    </form>
</div>
