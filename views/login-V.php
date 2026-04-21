<?php
require_once __DIR__ . "/../toolbox/tools.php"; 
$title = "Connexion";
require __DIR__ . "/components/head.php"
?>

<body>

<?php require_once __DIR__ . "/components/header.php"; ?>

<main class="login">
<h2>Connexion</h2>

<form action="/M-Motors/controllers/login-C.php" method="POST">

    <label for="email">Adresse email :</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>

</form>
</main>

</body>
</html>