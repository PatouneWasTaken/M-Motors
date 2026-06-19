<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Véhicules";
require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main>
	
	<?php require_once __DIR__ . "/components/filters.php"; ?>

	<div class="fade" id="vehicles-container"></div>

</main>

<script src="/scripts/vehicles-list.js"></script>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>