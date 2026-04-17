<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>

<h2>Connexion</h2>

<form action="/M-Motors/controllers/login-control.php" method="POST">

    <label for="email">Adresse email :</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>

</form>

</body>
</html>