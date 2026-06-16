<?php
require_once __DIR__ . "/../../toolbox/tools.php"; 
$title = "Modifier un véhicule";
require __DIR__ . "/../components/head.php"
?>

<body>

<?php require_once __DIR__ . "/../components/header.php"; ?>

<main>

	<section class="admin-grid">

    	<div class="admin-form">
        	<h2>Modifier un véhicule</h2>

        	<form action="/M-Motors/public/index.php?page=admin_edit_vehicle&id=<?= (int)$vehicle['id'] ?>" method="POST" enctype="multipart/form-data">

            	<input name="brand" placeholder="Marque" value="<?= e($vehicle['brand']) ?>" required>
				<input name="model" placeholder="Model" value="<?= e($vehicle['model']) ?>" required>

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

				<textarea id="description" name="description" placeholder="Description.." rows="4" cols="50" required><?= e($vehicle['description']) ?></textarea>

            	<button>Enregistrer</button>
        	</form>

			<a href="/M-Motors/public/index.php?page=dashboard" class="btn">← Retour au dashboard</a>
    	</div>

	</section>

</main>

<script src="/M-Motors/toolbox/tools.js"></script>

<?php require_once __DIR__ . "/../components/footer.php"; ?>

</body>
</html>
