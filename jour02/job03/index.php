<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job 03 - Nombres de 0 à 100 avec formatage</title>
</head>
<body>
    <h1>Job 03 - Affichage des nombres de 0 à 100 avec formatage conditionnel</h1>
    
    <?php
    for ($i = 0; $i <= 100; $i++) {
        if ($i == 42) {
            echo "La Plateforme_";
        }
        elseif ($i >= 0 && $i <= 20) {
            echo "<i>" . $i . "</i>";
        }
        elseif ($i >= 25 && $i <= 50) {
            echo "<u>" . $i . "</u>";
        }
        else {
            echo $i;
        }
        
        echo "<br />";
    }
    ?>
</body>
</html>