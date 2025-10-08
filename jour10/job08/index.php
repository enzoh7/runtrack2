<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 08 - Statistiques avancées des étudiants</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: 
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h2 { color: 
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0; }
        .stat-card { background: 
        .stat-card.primary { border-color: 
        .stat-card.success { border-color: 
        .stat-card.warning { border-color: 
        .stat-card.info { border-color: 
        .stat-number { font-size: 2.5em; font-weight: bold; margin: 15px 0; }
        .stat-card.primary .stat-number { color: 
        .stat-card.success .stat-number { color: 
        .stat-card.warning .stat-number { color: 
        .stat-card.info .stat-number { color: 
        .chart-container { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid 
        .age-bar { height: 30px; background: linear-gradient(90deg, 
        .age-label { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: white; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid 
        th { background-color: 
        tr:nth-child(even) { background-color: 
        .info { background: 
        .error { background: 
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Job 08 - Statistiques avancées des étudiants</h1>
        
        <?php
        $host = 'localhost';
        $dbname = 'jour09';
        $username = 'root';
        $password = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            
            $totalStmt = $pdo->query("SELECT COUNT(*) as total FROM etudiants");
            $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $hommes = $pdo->query("SELECT COUNT(*) as total FROM etudiants WHERE sexe = 'Homme'")->fetch()['total'];
            $femmes = $pdo->query("SELECT COUNT(*) as total FROM etudiants WHERE sexe = 'Femme'")->fetch()['total'];
            
            
            $ageStmt = $pdo->query("SELECT AVG(YEAR(CURDATE()) - YEAR(naissance)) as age_moyen FROM etudiants");
            $ageMoyen = round($ageStmt->fetch()['age_moyen'], 1);
            
            
            $plusJeuneStmt = $pdo->query("SELECT MIN(YEAR(CURDATE()) - YEAR(naissance)) as plus_jeune FROM etudiants");
            $plusJeune = $plusJeuneStmt->fetch()['plus_jeune'];
            
            $plusAgeStmt = $pdo->query("SELECT MAX(YEAR(CURDATE()) - YEAR(naissance)) as plus_age FROM etudiants");
            $plusAge = $plusAgeStmt->fetch()['plus_age'];
            
            echo '<div class="stats-grid">';
            
            echo '<div class="stat-card primary">';
            echo '<h3>👥 Total étudiants</h3>';
            echo '<div class="stat-number">' . $total . '</div>';
            echo '<p>Étudiants inscrits</p>';
            echo '</div>';
            
            echo '<div class="stat-card success">';
            echo '<h3>👨 Hommes</h3>';
            echo '<div class="stat-number">' . $hommes . '</div>';
            echo '<p>' . ($total > 0 ? round(($hommes / $total) * 100, 1) : 0) . '%</p>';
            echo '</div>';
            
            echo '<div class="stat-card warning">';
            echo '<h3>👩 Femmes</h3>';
            echo '<div class="stat-number">' . $femmes . '</div>';
            echo '<p>' . ($total > 0 ? round(($femmes / $total) * 100, 1) : 0) . '%</p>';
            echo '</div>';
            
            echo '<div class="stat-card info">';
            echo '<h3>🎂 Âge moyen</h3>';
            echo '<div class="stat-number">' . $ageMoyen . '</div>';
            echo '<p>années</p>';
            echo '</div>';
            
            echo '</div>';
            
            
            echo '<h2>📈 Répartition par âge</h2>';
            
            $ageRanges = [
                '18-25' => ['min' => 18, 'max' => 25],
                '26-35' => ['min' => 26, 'max' => 35],
                '36-45' => ['min' => 36, 'max' => 45],
                '46+' => ['min' => 46, 'max' => 999]
            ];
            
            echo '<div class="chart-container">';
            foreach ($ageRanges as $range => $limits) {
                $sql = "SELECT COUNT(*) as count FROM etudiants 
                        WHERE (YEAR(CURDATE()) - YEAR(naissance)) BETWEEN {$limits['min']} AND {$limits['max']}";
                $count = $pdo->query($sql)->fetch()['count'];
                $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                
                echo '<div style="margin: 10px 0;">';
                echo '<strong>' . $range . ' ans :</strong> ' . $count . ' étudiant(s) (' . round($percentage, 1) . '%)';
                echo '<div class="age-bar" style="width: ' . ($percentage * 3) . 'px; min-width: 50px;">';
                echo '<span class="age-label">' . $count . '</span>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            
            
            echo '<h2>🏆 Top des prénoms</h2>';
            $prenomsStmt = $pdo->query("
                SELECT prenom, COUNT(*) as count 
                FROM etudiants 
                GROUP BY prenom 
                ORDER BY count DESC, prenom ASC 
                LIMIT 10
            ");
            $prenoms = $prenomsStmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($prenoms) {
                echo '<table>';
                echo '<thead><tr><th>Rang</th><th>Prénom</th><th>Nombre</th><th>Pourcentage</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($prenoms as $index => $prenom) {
                    $pourcentage = $total > 0 ? round(($prenom['count'] / $total) * 100, 1) : 0;
                    echo '<tr>';
                    echo '<td>' . ($index + 1) . '</td>';
                    echo '<td>' . htmlspecialchars($prenom['prenom']) . '</td>';
                    echo '<td>' . $prenom['count'] . '</td>';
                    echo '<td>' . $pourcentage . '%</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
            }
            
            
            echo '<h2>ℹ️ Informations détaillées</h2>';
            
            
            $plusJeuneInfoStmt = $pdo->query("
                SELECT prenom, nom, naissance, (YEAR(CURDATE()) - YEAR(naissance)) as age
                FROM etudiants 
                ORDER BY naissance DESC 
                LIMIT 1
            ");
            $plusJeuneInfo = $plusJeuneInfoStmt->fetch(PDO::FETCH_ASSOC);
            
            
            $plusAgeInfoStmt = $pdo->query("
                SELECT prenom, nom, naissance, (YEAR(CURDATE()) - YEAR(naissance)) as age
                FROM etudiants 
                ORDER BY naissance ASC 
                LIMIT 1
            ");
            $plusAgeInfo = $plusAgeInfoStmt->fetch(PDO::FETCH_ASSOC);
            
            echo '<div class="info">';
            echo '<strong>👶 Plus jeune étudiant :</strong> ';
            if ($plusJeuneInfo) {
                echo htmlspecialchars($plusJeuneInfo['prenom'] . ' ' . $plusJeuneInfo['nom']) . 
                     ' (' . $plusJeuneInfo['age'] . ' ans, né(e) le ' . $plusJeuneInfo['naissance'] . ')';
            } else {
                echo 'Aucune donnée';
            }
            echo '<br>';
            
            echo '<strong>👴 Plus âgé étudiant :</strong> ';
            if ($plusAgeInfo) {
                echo htmlspecialchars($plusAgeInfo['prenom'] . ' ' . $plusAgeInfo['nom']) . 
                     ' (' . $plusAgeInfo['age'] . ' ans, né(e) le ' . $plusAgeInfo['naissance'] . ')';
            } else {
                echo 'Aucune donnée';
            }
            echo '</div>';
            
            
            echo '<h2>📧 Domaines email les plus utilisés</h2>';
            $domainesStmt = $pdo->query("
                SELECT 
                    SUBSTRING(email, LOCATE('@', email) + 1) as domaine,
                    COUNT(*) as count
                FROM etudiants 
                WHERE email LIKE '%@%'
                GROUP BY domaine 
                ORDER BY count DESC 
                LIMIT 5
            ");
            $domaines = $domainesStmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($domaines) {
                echo '<table>';
                echo '<thead><tr><th>Domaine</th><th>Nombre</th><th>Pourcentage</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($domaines as $domaine) {
                    $pourcentage = $total > 0 ? round(($domaine['count'] / $total) * 100, 1) : 0;
                    echo '<tr>';
                    echo '<td>@' . htmlspecialchars($domaine['domaine']) . '</td>';
                    echo '<td>' . $domaine['count'] . '</td>';
                    echo '<td>' . $pourcentage . '%</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
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

