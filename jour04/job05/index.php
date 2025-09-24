<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 05 - Formulaire de connexion</title>
</head>
<body>
    <form method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur">
        <input type="password" name="password" placeholder="Mot de passe">
        <input type="submit" value="Connexion">
    </form>
    <?php
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] === 'John' && $_POST['password'] === 'Rambo') {
            echo "C’est pas ma guerre";
        } else {
            echo "Votre pire cauchemar";
        }
    }
    ?>
</body>
</html>
