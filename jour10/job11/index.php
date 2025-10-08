<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job 11 - Capacité moyenne des salles</title>
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
            padding: 20px;
            text-align: center;
            font-size: 1.2em;
        }
        
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        td {
            background: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
            font-size: 2em;
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
        
        .stats-card {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            border-left: 5px solid #2196f3;
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
        <h1>📊 Job 11 - Capacité moyenne des salles</h1>
        
        <?php
        $host = 'localhost';
        $dbname = 'jour09';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->query("SELECT AVG(capacite) AS capacite_moyenne FROM salles");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && $result['capacite_moyenne'] !== null) {
                echo '<div class="success">';
                echo '✅ <strong>Calcul de la capacité moyenne réussi</strong>';
                echo '</div>';
                
                echo '<table>';
                echo '<thead><tr>';
                echo '<th>Capacité Moyenne</th>';
                echo '</tr></thead>';
                echo '<tbody>';
                echo '<tr>';
                echo '<td>' . number_format($result['capacite_moyenne'], 2) . ' personnes</td>';
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
                
                $statsQuery = $pdo->query("
                    SELECT 
                        COUNT(*) as nb_salles,
                        MIN(capacite) as min_capacite,
                        MAX(capacite) as max_capacite,
                        SUM(capacite) as capacite_totale
                    FROM salles
                ");
                $stats = $statsQuery->fetch(PDO::FETCH_ASSOC);
                
                echo '<div class="stats-card">';
                echo '<h3>📈 Statistiques complètes des salles</h3>';
                echo '<p><strong>Nombre total de salles:</strong> ' . $stats['nb_salles'] . '</p>';
                echo '<p><strong>Capacité minimale:</strong> ' . $stats['min_capacite'] . ' personnes</p>';
                echo '<p><strong>Capacité maximale:</strong> ' . $stats['max_capacite'] . ' personnes</p>';
                echo '<p><strong>Capacité totale:</strong> ' . $stats['capacite_totale'] . ' personnes</p>';
                echo '<p><strong>Capacité moyenne:</strong> ' . number_format($result['capacite_moyenne'], 2) . ' personnes</p>';
                echo '</div>';
                
            } else {
                echo '<div class="error">❌ Aucune donnée de capacité trouvée dans la base de données.</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="error">';
            echo '❌ <strong>Erreur de connexion:</strong><br>';
            echo htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
        
        <div class="navigation">
            <a href="../job10/index.php" class="nav-link">⬅️ Job 10</a>
            <a href="../index.php" class="nav-link">🏠 Menu</a>
            <a href="../job12/index.php" class="nav-link">➡️ Job 12</a>
        </div>
    </div>
</body>
</html>