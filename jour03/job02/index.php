<?php
$personnes = [
    ["nom" => "Dupont", "prenom" => "Jean", "age" => 30],
    ["nom" => "Martin", "prenom" => "Sophie", "age" => 25],
    ["nom" => "Durand", "prenom" => "Luc", "age" => 40],
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jour 3 - Job 02 (Tableaux associatifs)</title>
    <style>
        table { border-collapse: collapse; width: 60%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f6f6f6; }
    </style>
</head>
<body>
    <h1>Job 02 — Tableaux associatifs</h1>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Âge</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($personnes as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['nom']); ?></td>
                    <td><?php echo htmlspecialchars($p['prenom']); ?></td>
                    <td><?php echo (int)$p['age']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
