<?php
require_once __DIR__ . "/../../toolbox/tools.php"; 
$title = "Dashboard";
require __DIR__ . "/../components/head.php"
?>

<body>

<?php require_once __DIR__ . "/../components/header.php"; ?>

<main>

	<section class="admin-grid">

    	<!-- add form -->
    	<div class="admin-form">

        	<form id="addVehicleForm" enctype="multipart/form-data">
            	<input name="brand" placeholder="Marque" required>
				<input name="model" placeholder="Model" required>

            	<input name="kms" type="number" placeholder="Kilométrage" min="0" required>

            	<select name="type">
                	<option value="sale">Vente</option>
                	<option value="rent">Location</option>
            	</select>

            	<input name="price" type="number" placeholder="Prix" required>

            	<input type="file" name="image" accept="image/*" onchange="previewImage(event)" required>

				<textarea id="description" name="description" placeholder="Description.." rows="4" cols="50" required></textarea>

            	<button>Ajouter un véhicule</button>
        	</form>

        	<p id="form-message"></p>

			<!-- aperçu de l'image, sous le formulaire -->
			<img id="preview" style="max-width:200px; display:none;">
    	</div>

    	<!-- filtres -->
    	<?php $showTypeFilter = true; require_once __DIR__ . "/../components/filters.php"; ?>

    	<!-- liste -->
        <div class="admin-list" id="admin-vehicles-container"></div>

	</section>

</main>

<script src="/scripts/admin.js"></script>
<script src="/scripts/tools.js"></script>

<?php require_once __DIR__ . "/../components/footer.php"; ?>

</body>
</html>
