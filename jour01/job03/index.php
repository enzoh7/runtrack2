<?php
$estVrai = true;
$monAge = 25;

$monNom = "Jean Dupont";

$monPoids = 75.5;

function obtenirType($variable) {
    return gettype($variable);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job 03 - Types de variables PHP</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Variables PHP et leurs types</h1>
    
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Nom</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo obtenirType($estVrai); ?></td>
                <td>$estVrai</td>
                <td><?php echo $estVrai ? 'true' : 'false'; ?></td>
            </tr>
            <tr>
                <td><?php echo obtenirType($monAge); ?></td>
                <td>$monAge</td>
                <td><?php echo $monAge; ?></td>
            </tr>
            <tr>
                <td><?php echo obtenirType($monNom); ?></td>
                <td>$monNom</td>
                <td><?php echo $monNom; ?></td>
            </tr>
            <tr>
                <td><?php echo obtenirType($monPoids); ?></td>
                <td>$monPoids</td>
                <td><?php echo $monPoids; ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>