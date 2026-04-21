<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Déposer un dossier";
require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main>

<h1>Déposer un dossier</h1>

<form action="/index.php?page=submit_app" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="vehicle_id" value="<?= (int)($_GET['vehicle_id'] ?? 0) ?>">

    <label>Nom complet :</label>
    <input type="text" name="name" required>

    <label>Email :</label>
    <input type="email" name="email" required>

    <label>Document (PDF) :</label>
    <input type="file" name="document" required>

    <button type="submit">Envoyer</button>

</form>

</main>

<?php require_once __DIR__ . "/components/footer.php"; ?>

</body>
</html>