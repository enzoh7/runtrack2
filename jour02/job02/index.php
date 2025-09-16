<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job 02 - Nombres de 0 à 1337 avec exclusions</title>
</head>
<body>
    <h1>Job 02 - Affichage des nombres de 0 à 1337 (sauf 26, 37, 88, 1111, 3233)</h1>
    
    <?php
    $nombresExclus = [26, 37, 88, 1111, 3233];
    for ($i = 0; $i <= 1337; $i++) {
        if (!in_array($i, $nombresExclus)) {
            echo $i;
            echo "<br />";
        }
    }
    ?>
</body>
</html>
