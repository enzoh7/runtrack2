<?php
$inventaire = [
    ["categorie" => "Fruits", "items" => ["Pomme"=>10, "Banane"=>5, "Orange"=>8]],
    ["categorie" => "Legumes", "items" => ["Carotte"=>12, "Tomate"=>7]],
    ["categorie" => "Boissons", "items" => ["Eau"=>50, "Jus"=>20]],
];

function totalCategorie($items) {
    $total = 0;
    foreach ($items as $qte) {
        $total += $qte;
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jour 3 - Job 03 (Tableaux multidimensionnels)</title>
    <style>
        table { border-collapse: collapse; width: 70%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
    <h1>Job 03 — Inventaire (tableau multidimensionnel)</h1>

    <?php foreach ($inventaire as $cat): ?>
        <h2><?php echo htmlspecialchars($cat['categorie']); ?> (Total: <?php echo totalCategorie($cat['items']); ?>)</h2>
        <table>
            <thead>
                <tr><th>Article</th><th>Quantité</th></tr>
            </thead>
            <tbody>
                <?php foreach ($cat['items'] as $article => $qte): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article); ?></td>
                        <td><?php echo (int)$qte; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</body>
</html>
