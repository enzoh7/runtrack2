<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job 01 - Nombres de 0 à 1337</title>
</head>
<body>
    <h1>Job 01 - Affichage des nombres de 0 à 1337</h1>
    
    <?php
    // Boucle pour afficher tous les nombres de 0 à 1337
    for ($i = 0; $i <= 1337; $i++) {
        // Condition spéciale pour le nombre 42
        if ($i == 42) {
            echo "<b><u>" . $i . "</u></b>";
        } else {
            echo $i;
        }
        
        // Ajouter un retour à la ligne après chaque nombre
        echo "<br />";
    }
    ?>
</body>
</html>