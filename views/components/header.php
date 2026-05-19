<header>

    <?php
	$currentType = $_GET['type'] ?? null;

	if (!in_array($currentType, ['sale', 'rent'])) {
    	$currentType = null;
	}
	?>

	<span class='left-nav'>

		<img src="/M-Motors/public/assets/letter-m.png" alt="Logo M-Motors">

		<nav>
		
			<a href="/M-Motors/public/index.php" class="btn <?= !$currentType ? 'active' : '' ?>">
				Tous
			</a>

			<a href="/M-Motors/public/index.php/?type=sale" class="btn <?= $currentType === 'sale' ? 'active' : '' ?>">
       			Vente
			</a>

			<a href="/M-Motors/public/index.php/?type=rent" class="btn <?= $currentType === 'rent' ? 'active' : '' ?>">
       			Location
			</a>

		</nav>
	</span>

	<nav class='right-nav'>

		<a href="/M-Motors/public/index.php/?page=dashboard">
			Dashboard
		</a>

		<a href="/M-Motors/public/index.php/?page=account" class='profile'>
			<img src="/M-Motors/public/assets/letter-m.png" alt="">
		</a>

	</nav>

</header>