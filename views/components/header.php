<header>

    <?php
	$currentType = $_GET['type'] ?? null;

	if (!in_array($currentType, ['sale', 'rent'])) {
    	$currentType = null;
	}
	?>

	<nav class="filters">
		
		<a href="/index.php" class="btn <?= !$currentType ? 'active' : '' ?>">
			Tous
		</a>

		<a href="/index.php?type=sale" class="btn <?= $currentType === 'sale' ? 'active' : '' ?>">
       		Vente
		</a>

		<a href="/index.php?type=rent" class="btn <?= $currentType === 'rent' ? 'active' : '' ?>">
       		Location
		</a>

	</nav>

</header>