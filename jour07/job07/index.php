<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 07 - Maison ASCII</title>
    <style>pre{font-family:monospace;}</style>
</head>
<body>
    <form method="get">
        <input type="text" name="largeur" placeholder="Largeur">
        <input type="text" name="hauteur" placeholder="Hauteur">
        <input type="submit" value="Afficher la maison">
    </form>
    <?php
    function dessinerMaison($largeur, $hauteur) {
        $largeur = intval($largeur);
        $hauteur = intval($hauteur);
        if ($largeur < 2 || $hauteur < 2) return;
        // Toit
        for ($i = 0; $i < $largeur; $i++) {
            echo str_repeat(' ', $largeur - $i - 1);
            echo str_repeat('/', 1);
            echo str_repeat(' ', $i * 2);
            echo str_repeat('\\', 1);
            echo "<br>";
        }
        // Corps
        for ($i = 0; $i < $hauteur; $i++) {
            echo '|'.str_repeat(' ', $largeur * 2 - 2).'|<br>';
        }
        // Sol
        echo '+' . str_repeat('-', $largeur * 2 - 2) . '+<br>';
    }
    if (isset($_GET['largeur']) && isset($_GET['hauteur']) && is_numeric($_GET['largeur']) && is_numeric($_GET['hauteur'])) {
        echo '<pre>';
        dessinerMaison($_GET['largeur'], $_GET['hauteur']);
        echo '</pre>';
    }
    ?>
</body>
</html>
