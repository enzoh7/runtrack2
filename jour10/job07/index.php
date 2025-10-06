<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 07 - Tri des étudiants</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        .sort-controls { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
        .sort-btn { padding: 10px 15px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
        .sort-btn.active { background: #007cba; color: white; }
        .sort-btn:not(.active) { background: #e9ecef; color: #495057; }
        .sort-btn:hover { opacity: 0.8; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #007cba; color: white; font-weight: bold; cursor: pointer; position: relative; }
        th:hover { background-color: #0056b3; }
        th.sortable::after { content: ' ⇅'; opacity: 0.5; }
        th.asc::after { content: ' ↑'; opacity: 1; }
        th.desc::after { content: ' ↓'; opacity: 1; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f0f8ff; }
        .info { background: #e7f3ff; padding: 15px; border-left: 4px solid #007cba; margin: 20px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Job 07 - Tri des étudiants</h1>
        
        <?php
        $host = 'localhost';
        $dbname = 'jour09';
        $username = 'root';
        $password = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Paramètres de tri
            $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'nom';
            $order = isset($_GET['order']) ? $_GET['order'] : 'asc';
            
            // Validation des paramètres
            $validSorts = ['id', 'prenom', 'nom', 'naissance', 'sexe', 'email'];
            $validOrders = ['asc', 'desc'];
            
            if (!in_array($sortBy, $validSorts)) $sortBy = 'nom';
            if (!in_array($order, $validOrders)) $order = 'asc';
            
            // Affichage des contrôles de tri
            echo '<div class="sort-controls">';
            echo '<h3>📋 Options de tri</h3>';
            echo '<p>Cliquez sur une option pour trier les étudiants :</p>';
            
            $sortOptions = [
                'nom' => '📝 Nom',
                'prenom' => '👤 Prénom', 
                'naissance' => '🎂 Date de naissance',
                'sexe' => '⚧ Sexe',
                'email' => '📧 Email',
                'id' => '🆔 ID'
            ];
            
            foreach ($sortOptions as $field => $label) {
                $newOrder = ($sortBy == $field && $order == 'asc') ? 'desc' : 'asc';
                $activeClass = ($sortBy == $field) ? 'active' : '';
                $arrow = '';
                if ($sortBy == $field) {
                    $arrow = ($order == 'asc') ? ' ↑' : ' ↓';
                }
                
                echo '<a href="?sort=' . $field . '&order=' . $newOrder . '" class="sort-btn ' . $activeClass . '">';
                echo $label . $arrow;
                echo '</a>';
            }
            echo '</div>';
            
            // Construction de la requête SQL
            $sql = "SELECT * FROM etudiants ORDER BY $sortBy $order";
            if ($sortBy != 'nom') {
                $sql .= ", nom ASC"; // Tri secondaire par nom
            }
            
            $stmt = $pdo->query($sql);
            $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($etudiants) {
                echo '<div class="info">';
                echo '<strong>📊 Résultats :</strong> ' . count($etudiants) . ' étudiant(s) triés par ';
                echo $sortOptions[$sortBy] . ' (' . ($order == 'asc' ? 'croissant' : 'décroissant') . ')';
                echo '</div>';
                
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                
                // En-têtes cliquables
                $headers = [
                    'id' => '🆔 ID',
                    'prenom' => '👤 Prénom',
                    'nom' => '📝 Nom',
                    'naissance' => '🎂 Naissance',
                    'sexe' => '⚧ Sexe',
                    'email' => '📧 Email'
                ];
                
                foreach ($headers as $field => $label) {
                    $newOrder = ($sortBy == $field && $order == 'asc') ? 'desc' : 'asc';
                    $class = 'sortable';
                    if ($sortBy == $field) {
                        $class .= ' ' . $order;
                    }
                    
                    echo '<th class="' . $class . '">';
                    echo '<a href="?sort=' . $field . '&order=' . $newOrder . '" style="color: white; text-decoration: none;">';
                    echo $label;
                    echo '</a>';
                    echo '</th>';
                }
                
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                
                foreach ($etudiants as $index => $etudiant) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($etudiant['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['prenom']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['nom']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['naissance']) . '</td>';
                    echo '<td>';
                    if ($etudiant['sexe'] == 'Homme') {
                        echo '👨 ' . htmlspecialchars($etudiant['sexe']);
                    } else {
                        echo '👩 ' . htmlspecialchars($etudiant['sexe']);
                    }
                    echo '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['email']) . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                
            } else {
                echo '<div class="info">';
                echo '<strong>📝 Aucun étudiant dans la base de données</strong>';
                echo '</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="error">';
            echo '<strong>❌ Erreur de base de données :</strong><br>';
            echo htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>