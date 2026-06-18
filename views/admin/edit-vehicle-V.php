<?php
require_once __DIR__ . "/../../toolbox/tools.php"; 
$title = "Modifier un véhicule";

$error = $_SESSION['edit_error'] ?? null;
unset($_SESSION['edit_error']);

require __DIR__ . "/../components/head.php"
?>

<body>

<?php require_once __DIR__ . "/../components/header.php"; ?>

<main class="edit-vehicle">

	<a href="/M-Motors/public/index.php?page=dashboard" class="btn">← Retour au dashboard</a>

	<section class="admin-grid">

    	<div class="admin-form">

        	<form action="/M-Motors/public/index.php?page=admin_edit_vehicle&id=<?= (int)$vehicle['id'] ?>" method="POST" enctype="multipart/form-data">

            	<input name="brand" placeholder="Marque" value="<?= e($vehicle['brand']) ?>" required>
				<input name="model" placeholder="Model" value="<?= e($vehicle['model']) ?>" required>

            	<input name="kms" type="number" placeholder="Kilométrage" min="0" value="<?= (int)$vehicle['kms'] ?>" required>

            	<select name="type">
                	<option value="sale" <?= $vehicle['type'] === 'sale' ? 'selected' : '' ?>>Vente</option>
                	<option value="rent" <?= $vehicle['type'] === 'rent' ? 'selected' : '' ?>>Location</option>
            	</select>

            	<input name="price" type="number" placeholder="Prix" value="<?= (int)$vehicle['price'] ?>" required>

				<p>Photo actuelle :</p>
				<img src="/M-Motors/uploads/<?= e($vehicle['photo']) ?>" alt="" style="max-width:200px;">

            	<input type="file" name="image" accept="image/*" onchange="previewImage(event)">
				<img id="preview" style="max-width:200px; display:none;">
				<small>Laisser vide pour conserver la photo actuelle.</small>

				<br>

				<textarea id="description" name="description" placeholder="Description.." rows="4" cols="50" required><?= e($vehicle['description']) ?></textarea>

            	<button>Enregistrer</button>
        	</form>

			<?php if ($error) : ?>
				<p class="form-error"><?= e($error) ?></p>
			<?php endif; ?>

    	</div>

	</section>

</main>

<script src="/M-Motors/toolbox/tools.js"></script>

<?php require_once __DIR__ . "/../components/footer.php"; ?>

</body>
</html>
