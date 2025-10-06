<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 06 - Étudiants par sexe</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        .stats-container { display: flex; justify-content: space-around; margin: 30px 0; }
        .stat-card { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; min-width: 200px; border: 2px solid #dee2e6; }
        .stat-card.male { border-color: #007bff; background: #e7f1ff; }
        .stat-card.female { border-color: #e83e8c; background: #fce7f3; }
        .stat-number { font-size: 3em; font-weight: bold; margin: 10px 0; }
        .stat-card.male .stat-number { color: #007bff; }
        .stat-card.female .stat-number { color: #e83e8c; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #007cba; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .filter-buttons { text-align: center; margin: 20px 0; }
        .filter-btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
        .filter-btn.all { background: #6c757d; color: white; }
        .filter-btn.male { background: #007bff; color: white; }
        .filter-btn.female { background: #e83e8c; color: white; }
        .filter-btn:hover { opacity: 0.8; }
        .info { background: #e7f3ff; padding: 15px; border-left: 4px solid #007cba; margin: 20px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚧ Job 06 - Répartition par sexe</h1>
        
        <?php
        $host = 'localhost';
        $dbname = 'jour09';
        $username = 'root';
        $password = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Statistiques générales
            $totalStmt = $pdo->query("SELECT COUNT(*) as total FROM etudiants");
            $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $hommeStmt = $pdo->query("SELECT COUNT(*) as total FROM etudiants WHERE sexe = 'Homme'");
            $totalHommes = $hommeStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $femmeStmt = $pdo->query("SELECT COUNT(*) as total FROM etudiants WHERE sexe = 'Femme'");
            $totalFemmes = $femmeStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Affichage des statistiques
            echo '<div class="info">';
            echo '<strong>📊 Statistiques de répartition par sexe</strong>';
            echo '</div>';
            
            echo '<div class="stats-container">';
            echo '<div class="stat-card male">';
            echo '<h3>👨 Hommes</h3>';
            echo '<div class="stat-number">' . $totalHommes . '</div>';
            echo '<p>' . ($total > 0 ? round(($totalHommes / $total) * 100, 1) : 0) . '%</p>';
            echo '</div>';
            
            echo '<div class="stat-card female">';
            echo '<h3>👩 Femmes</h3>';
            echo '<div class="stat-number">' . $totalFemmes . '</div>';
            echo '<p>' . ($total > 0 ? round(($totalFemmes / $total) * 100, 1) : 0) . '%</p>';
            echo '</div>';
            echo '</div>';
            
            // Boutons de filtrage
            echo '<div class="filter-buttons">';
            echo '<a href="?" class="filter-btn all">👥 Tous (' . $total . ')</a>';
            echo '<a href="?sexe=Homme" class="filter-btn male">👨 Hommes (' . $totalHommes . ')</a>';
            echo '<a href="?sexe=Femme" class="filter-btn female">👩 Femmes (' . $totalFemmes . ')</a>';
            echo '</div>';
            
            // Récupération des étudiants selon le filtre
            $filtre = isset($_GET['sexe']) ? $_GET['sexe'] : null;
            
            if ($filtre && in_array($filtre, ['Homme', 'Femme'])) {
                $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE sexe = :sexe ORDER BY nom, prenom");
                $stmt->execute(['sexe' => $filtre]);
                $titre = ($filtre == 'Homme') ? '👨 Étudiants hommes' : '👩 Étudiantes femmes';
            } else {
                $stmt = $pdo->query("SELECT * FROM etudiants ORDER BY sexe, nom, prenom");
                $titre = '👥 Tous les étudiants';
            }
            
            $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($etudiants) {
                echo '<h2>' . $titre . '</h2>';
                echo '<table>';
                echo '<thead><tr><th>ID</th><th>Prénom</th><th>Nom</th><th>Naissance</th><th>Sexe</th><th>Email</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($etudiants as $etudiant) {
                    // Style conditionnel selon le sexe
                    $rowClass = ($etudiant['sexe'] == 'Homme') ? 'style="background-color: #e7f1ff;"' : 
                               (($etudiant['sexe'] == 'Femme') ? 'style="background-color: #fce7f3;"' : '');
                    
                    echo '<tr ' . $rowClass . '>';
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
                
                echo '<div class="info">';
                if ($filtre) {
                    echo '<strong>Résultats :</strong> ' . count($etudiants) . ' étudiant(s) de sexe "' . htmlspecialchars($filtre) . '"';
                } else {
                    echo '<strong>Total :</strong> ' . count($etudiants) . ' étudiant(s) dans la base de données';
                }
                echo '</div>';
                
            } else {
                echo '<div class="info">';
                if ($filtre) {
                    echo '<strong>Aucun étudiant de sexe "' . htmlspecialchars($filtre) . '" trouvé</strong>';
                } else {
                    echo '<strong>Aucun étudiant dans la base de données</strong>';
                }
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