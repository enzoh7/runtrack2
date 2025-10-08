<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job 13 - Salles et leurs étages (JOIN)</title>
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
        
        .join-info {
            background: #e1f5fe;
            color: #01579b;
            border: 1px solid #81d4fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .etage-badge {
            background: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.9em;
            font-weight: bold;
        }
        
        .salle-name {
            font-weight: bold;
            color: #2c3e50;
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
        
        .sql-example {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            margin: 20px 0;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏢 Job 13 - Salles et leurs étages (JOIN)</h1>
        
        <div class="join-info">
            <strong>🔗 Requête JOIN:</strong> Cette page utilise une jointure entre les tables 'salles' et 'etage' pour récupérer le nom des salles et le nom de leur étage correspondant.
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
                SELECT s.nom AS nom_salle, e.nom AS nom_etage 
                FROM salles s 
                INNER JOIN etage e ON s.id_etage = e.id 
                ORDER BY e.nom ASC, s.nom ASC
            ");
            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($resultats) {
                echo '<div class="success">';
                echo '✅ <strong>' . count($resultats) . ' association(s) trouvée(s)</strong> entre salles et étages';
                echo '</div>';
                
                echo '<table>';
                echo '<thead><tr>';
                echo '<th>Nom de la salle</th>';
                echo '<th>Nom de l\'étage</th>';
                echo '</tr></thead>';
                echo '<tbody>';
                
                foreach ($resultats as $row) {
                    echo '<tr>';
                    echo '<td><span class="salle-name">' . htmlspecialchars($row['nom_salle']) . '</span></td>';
                    echo '<td><span class="etage-badge">' . htmlspecialchars($row['nom_etage']) . '</span></td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                
                $statsQuery = $pdo->query("
                    SELECT 
                        e.nom as etage_nom,
                        COUNT(s.id) as nb_salles
                    FROM etage e
                    LEFT JOIN salles s ON e.id = s.id_etage
                    GROUP BY e.id, e.nom
                    ORDER BY nb_salles DESC
                ");
                $statsEtages = $statsQuery->fetchAll(PDO::FETCH_ASSOC);
                
                echo '<div class="success">';
                echo '📊 <strong>Répartition des salles par étage:</strong><br>';
                foreach ($statsEtages as $stat) {
                    echo '• <strong>' . htmlspecialchars($stat['etage_nom']) . ':</strong> ' . $stat['nb_salles'] . ' salle(s)<br>';
                }
                echo '</div>';
                
            } else {
                echo '<div class="error">❌ Aucune association trouvée entre les salles et les étages.</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="error">';
            echo '❌ <strong>Erreur de connexion:</strong><br>';
            echo htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
        
        <div class="sql-example">
            <strong>📝 Requête SQL utilisée:</strong><br>
            SELECT s.nom AS nom_salle, e.nom AS nom_etage<br>
            FROM salles s<br>
            INNER JOIN etage e ON s.id_etage = e.id<br>
            ORDER BY e.nom ASC, s.nom ASC;
        </div>
        
        <div class="navigation">
            <a href="../job12/index.php" class="nav-link">⬅️ Job 12</a>
            <a href="../index.php" class="nav-link">🏠 Menu</a>
            <a href="../job09/index.php" class="nav-link">📊 Retour job 09</a>
        </div>
    </div>
</body>
</html>