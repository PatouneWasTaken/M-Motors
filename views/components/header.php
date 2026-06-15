<header>

    <?php
	$currentType = $_GET['type'] ?? null;

	if (!in_array($currentType, ['sale', 'rent'])) {
    	$currentType = null;
	};
	?>

	<span class='left-nav'>

		<img src="/M-Motors/public/assets/letter-m.png" alt="Logo M-Motors">

		<nav>
		
			<a href="/M-Motors/public/index.php" class="btn <?= !$currentType ? 'active' : '' ?>">
				Tous
			</a>

			<a href="/M-Motors/public/index.php?type=sale" class="btn <?= $currentType === 'sale' ? 'active' : '' ?>">
       			Vente
			</a>

			<a href="/M-Motors/public/index.php?type=rent" class="btn <?= $currentType === 'rent' ? 'active' : '' ?>">
       			Location
			</a>

		</nav>
	</span>

	<nav class='right-nav'>

		<?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) { ?>
    		<a href="/M-Motors/public/index.php?page=dashboard">
        		Dashboard
    		</a>
		<?php } ?>

		<?php if (isset($_SESSION['user_id'])) { ?>
			<a href="/M-Motors/public/index.php?page=account" class='profile'>
				<img src="/M-Motors/public/assets/user.png" alt="Mon compte">
			</a>
			<a href="/M-Motors/public/index.php?page=logout" class="btn">
				Déconnexion
			</a>
		<?php } else { ?>
			<a href="/M-Motors/public/index.php?page=login" class="btn">
				Connexion
			</a>
		<?php } ?>

	</nav>

</header>
