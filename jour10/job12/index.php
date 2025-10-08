<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job 12 - Étudiants nés entre 1998 et 2018</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e3f2fd;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }
        
        .message {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-weight: bold;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .filter-info {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .age-badge {
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.9em;
            font-weight: bold;
        }
        
        .navigation {
            text-align: center;
            margin: 30px 0;
        }
        
        .nav-link {
            display: inline-block;
            margin: 0 10px;
            padding: 12px 25px;
            background: #34495e;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background: #2c3e50;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👥 Job 12 - Étudiants nés entre 1998 et 2018</h1>
        
        <div class="filter-info">
            <strong>🔍 Critères de recherche:</strong> Étudiants nés entre le 1er janvier 1998 et le 31 décembre 2018
        </div>
        
        <?php
        $host = 'localhost';
        $dbname = 'jour09';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->query("
                SELECT prenom, nom, naissance 
                FROM etudiants 
                WHERE YEAR(naissance) BETWEEN 1998 AND 2018 
                ORDER BY naissance DESC
            ");
            $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($etudiants) {
                echo '<div class="success">';
                echo '✅ <strong>' . count($etudiants) . ' étudiant(s) trouvé(s)</strong> né(s) entre 1998 et 2018';
                echo '</div>';
                
                echo '<table>';
                echo '<thead><tr>';
                echo '<th>Prénom</th>';
                echo '<th>Nom</th>';
                echo '<th>Date de naissance</th>';
                echo '<th>Âge actuel</th>';
                echo '</tr></thead>';
                echo '<tbody>';
                
                foreach ($etudiants as $etudiant) {
                    $dateNaissance = new DateTime($etudiant['naissance']);
                    $aujourdhui = new DateTime();
                    $age = $aujourdhui->diff($dateNaissance)->y;
                    
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($etudiant['prenom']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['nom']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['naissance']) . '</td>';
                    echo '<td><span class="age-badge">' . $age . ' ans</span></td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                
                $anneeMin = min(array_map(function($e) { return date('Y', strtotime($e['naissance'])); }, $etudiants));
                $anneeMax = max(array_map(function($e) { return date('Y', strtotime($e['naissance'])); }, $etudiants));
                
                echo '<div class="success">';
                echo '📊 <strong>Répartition:</strong><br>';
                echo '• Année de naissance la plus ancienne: ' . $anneeMin . '<br>';
                echo '• Année de naissance la plus récente: ' . $anneeMax;
                echo '</div>';
                
            } else {
                echo '<div class="error">❌ Aucun étudiant trouvé né entre 1998 et 2018.</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="error">';
            echo '❌ <strong>Erreur de connexion:</strong><br>';
            echo htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
        
        <div class="navigation">
            <a href="../job11/index.php" class="nav-link">⬅️ Job 11</a>
            <a href="../index.php" class="nav-link">🏠 Menu</a>
            <a href="../job13/index.php" class="nav-link">➡️ Job 13</a>
        </div>
    </div>
</body>
</html>