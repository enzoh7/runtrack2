<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 06 - Nombre pair ou impair</title>
</head>
<body>
    <form method="get">
        <input type="text" name="nombre" placeholder="Entrez un nombre">
        <input type="submit" value="Vérifier">
    </form>
    <?php
    if (isset($_GET['nombre']) && is_numeric($_GET['nombre'])) {
        if ($_GET['nombre'] % 2 == 0) {
            echo "Nombre pair";
        } else {
            echo "Nombre impair";
        }
    }
    ?>
</body>
</html>
