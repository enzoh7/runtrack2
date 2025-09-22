<?php
$fruits = ["Pomme", "Banane", "Fraise", "Kiwi", "Mangue"];
$count = count($fruits);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">eazésq
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jour 3 - Job 01 (Tableaux simples)</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 16px; }
        table { border-collapse: collapse; width: 50%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
    <h1>Job 01 — Tableau indexé</h1>
    <p>Tableau contenant <?php echo $count; ?> fruits :</p>

    <ul>
        <?php foreach ($fruits as $fruit): ?>
            <li><?php echo $fruit; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Affichage dans un tableau HTML</h2>
    <table>
        <thead>
            <tr>
                <th>Index</th>
                <th>Fruit</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < $count; $i++): ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $fruits[$i]; ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</body>
</html>
