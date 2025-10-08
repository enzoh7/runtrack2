<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job 10 - Salles triées par capacité croissante</title>
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
            transform: scale(1.02);
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
        <h1>🏢 Job 10 - Salles triées par capacité croissante</h1>
        
        <?php
        $host = 'localhost';
        $dbname = 'jour09';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->query("SELECT * FROM salles ORDER BY capacite ASC");
            $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($salles) {
                echo '<div class="success">';
                echo '✅ <strong>' . count($salles) . ' salle(s) trouvée(s)</strong> - Triées par capacité croissante';
                echo '</div>';
                
                echo '<table>';
                echo '<thead><tr>';
                foreach (array_keys($salles[0]) as $column) {
                    echo '<th>' . ucfirst($column) . '</th>';
                }
                echo '</tr></thead>';
                echo '<tbody>';
                
                foreach ($salles as $salle) {
                    echo '<tr>';
                    foreach ($salle as $value) {
                        echo '<td>' . htmlspecialchars($value) . '</td>';
                    }
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                
                echo '<div class="success">';
                echo '📊 <strong>Capacité minimum:</strong> ' . $salles[0]['capacite'] . ' personnes<br>';
                echo '📊 <strong>Capacité maximum:</strong> ' . end($salles)['capacite'] . ' personnes';
                echo '</div>';
                
            } else {
                echo '<div class="error">❌ Aucune salle trouvée dans la base de données.</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="error">';
            echo '❌ <strong>Erreur de connexion:</strong><br>';
            echo htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
        
        <div class="navigation">
            <a href="../job09/index.php" class="nav-link">⬅️ Job 09</a>
            <a href="../index.php" class="nav-link">🏠 Menu</a>
            <a href="../job11/index.php" class="nav-link">➡️ Job 11</a>
        </div>
    </div>
</body>
</html>