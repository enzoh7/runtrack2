<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 04 - Tableau des arguments POST</title>
</head>
<body>
    <form method="post">
        <input type="text" name="prenom" placeholder="Prénom">
        <input type="text" name="nom" placeholder="Nom">
        <input type="text" name="age" placeholder="Âge">
        <input type="submit" value="Envoyer">
    </form>
    <?php
    if (!empty($_POST)) {
        echo '<table border="1"><tr><th>Argument</th><th>Valeur</th></tr>';
        foreach ($_POST as $key => $value) {
            echo "<tr><td>$key</td><td>$value</td></tr>";
        }
        echo '</table>';
    }
    ?>
</body>
</html>
