<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>

<h2>Inscription</h2>

<form action="/M-Motors/controllers/register-control.php" method="POST">

    <label for="firstname">Prénom :</label>
    <input type="text" id="firstname" name="firstname" required><br><br>

	<label for="lastname">Nom :</label>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="email">Adresse email :</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">S’inscrire</button>

</form>

</body>
</html>