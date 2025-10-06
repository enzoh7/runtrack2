<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 09 - Gestion et affichage des salles</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        .tabs { display: flex; margin-bottom: 20px; border-bottom: 2px solid #dee2e6; }
        .tab { padding: 15px 25px; background: #f8f9fa; border: none; cursor: pointer; font-weight: bold; border-radius: 8px 8px 0 0; margin-right: 5px; }
        .tab.active { background: #007cba; color: white; }
        .tab:hover:not(.active) { background: #e9ecef; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #007cba; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f0f8ff; }
        .capacity-bar { height: 20px; background: linear-gradient(90deg, #28a745, #ffc107, #dc3545); border-radius: 10px; position: relative; margin: 5px 0; }
        .capacity-label { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); color: white; font-weight: bold; font-size: 12px; }
        .info { background: #e7f3ff; padding: 15px; border-left: 4px solid #007cba; margin: 20px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-card { background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; border: 2px solid #dee2e6; }
        .stat-number { font-size: 2em; font-weight: bold; color: #007cba; margin: 10px 0; }
        .filter-form { background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; }
        select { padding: 10px; border: 1px solid #ced4da; border-radius: 5px; margin: 0 10px; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-primary { background: #007cba; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { opacity: 0.9; }
    </style>
    <script>
        function showTab(tabName) {
            // Cacher tous les contenus
            var contents = document.getElementsByClassName('tab-content');
            for (var i = 0; i < contents.length; i++) {
                contents[i].style.display = 'none';
            }
            
            // Retirer la classe active de tous les onglets
            var tabs = document.getElementsByClassName('tab');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            
            // Afficher le contenu sélectionné et activer l'onglet
            document.getElementById(tabName).style.display = 'block';
            event.target.classList.add('active');
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>🏢 Job 09 - Gestion et affichage des salles</h1>
        
        <?php
        $host = 'localhost';
        $dbname = 'jour09';
        $username = 'root';
        $password = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Paramètres de filtrage
            $etageFilter = isset($_GET['etage']) ? $_GET['etage'] : '';
            $capaciteMin = isset($_GET['capacite_min']) ? $_GET['capacite_min'] : '';
            
            // Onglets de navigation
            echo '<div class="tabs">';
            echo '<button class="tab active" onclick="showTab(\'salles\')">🏢 Salles</button>';
            echo '<button class="tab" onclick="showTab(\'etages\')">🏗️ Étages</button>';
            echo '<button class="tab" onclick="showTab(\'stats\')">📊 Statistiques</button>';
            echo '<button class="tab" onclick="showTab(\'joined\')">🔗 Vue complète</button>';
            echo '</div>';
            
            // === ONGLET SALLES ===
            echo '<div id="salles" class="tab-content">';
            echo '<h2>🏢 Gestion des salles</h2>';
            
            // Formulaire de filtrage
            echo '<form method="GET" class="filter-form">';
            echo '<label><strong>Filtrer par étage :</strong></label>';
            echo '<select name="etage">';
            echo '<option value="">Tous les étages</option>';
            
            $etagesStmt = $pdo->query("SELECT * FROM etage ORDER BY numero");
            $etages = $etagesStmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($etages as $etage) {
                $selected = ($etageFilter == $etage['id']) ? 'selected' : '';
                echo '<option value="' . $etage['id'] . '" ' . $selected . '>Étage ' . $etage['numero'] . ' - ' . htmlspecialchars($etage['nom']) . '</option>';
            }
            echo '</select>';
            
            echo '<label><strong>Capacité minimum :</strong></label>';
            echo '<select name="capacite_min">';
            echo '<option value="">Toutes capacités</option>';
            echo '<option value="10"' . ($capaciteMin == '10' ? ' selected' : '') . '>10+ places</option>';
            echo '<option value="20"' . ($capaciteMin == '20' ? ' selected' : '') . '>20+ places</option>';
            echo '<option value="30"' . ($capaciteMin == '30' ? ' selected' : '') . '>30+ places</option>';
            echo '<option value="50"' . ($capaciteMin == '50' ? ' selected' : '') . '>50+ places</option>';
            echo '</select>';
            
            echo '<button type="submit" class="btn btn-primary">🔍 Filtrer</button>';
            echo '<a href="?" class="btn btn-secondary">🧹 Réinitialiser</a>';
            echo '</form>';
            
            // Construction de la requête avec filtres
            $sql = "SELECT s.*, e.nom as etage_nom, e.numero as etage_numero, COUNT(es.id_etudiant) as nb_etudiants
                    FROM salles s
                    LEFT JOIN etage e ON s.id_etage = e.id
                    LEFT JOIN etudiants_salles es ON s.id = es.id_salle
                    WHERE 1=1";
            
            $params = [];
            if (!empty($etageFilter)) {
                $sql .= " AND s.id_etage = :etage";
                $params['etage'] = $etageFilter;
            }
            if (!empty($capaciteMin)) {
                $sql .= " AND s.capacite >= :capacite_min";
                $params['capacite_min'] = $capaciteMin;
            }
            
            $sql .= " GROUP BY s.id ORDER BY e.numero, s.capacite DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($salles) {
                echo '<table>';
                echo '<thead><tr><th>ID</th><th>Nom</th><th>Étage</th><th>Capacité</th><th>Étudiants</th><th>Taux occupation</th><th>Jauge</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($salles as $salle) {
                    $tauxOccupation = $salle['capacite'] > 0 ? round(($salle['nb_etudiants'] / $salle['capacite']) * 100, 1) : 0;
                    
                    echo '<tr>';
                    echo '<td>' . $salle['id'] . '</td>';
                    echo '<td><strong>' . htmlspecialchars($salle['nom']) . '</strong></td>';
                    echo '<td>Étage ' . $salle['etage_numero'] . '<br><small>' . htmlspecialchars($salle['etage_nom']) . '</small></td>';
                    echo '<td>' . $salle['capacite'] . ' places</td>';
                    echo '<td>' . $salle['nb_etudiants'] . ' étudiant(s)</td>';
                    echo '<td>' . $tauxOccupation . '%</td>';
                    echo '<td>';
                    
                    $couleur = '#28a745'; // Vert
                    if ($tauxOccupation > 80) $couleur = '#dc3545'; // Rouge
                    elseif ($tauxOccupation > 60) $couleur = '#ffc107'; // Jaune
                    
                    echo '<div class="capacity-bar" style="width: ' . min($tauxOccupation * 2, 200) . 'px; background: ' . $couleur . ';">';
                    echo '<span class="capacity-label">' . $tauxOccupation . '%</span>';
                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="info">Aucune salle trouvée avec ces critères</div>';
            }
            echo '</div>';
            
            // === ONGLET ÉTAGES ===
            echo '<div id="etages" class="tab-content" style="display: none;">';
            echo '<h2>🏗️ Gestion des étages</h2>';
            
            if ($etages) {
                echo '<table>';
                echo '<thead><tr><th>ID</th><th>Nom</th><th>Numéro</th><th>Superficie</th><th>Nb Salles</th><th>Capacité totale</th><th>Étudiants</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($etages as $etage) {
                    // Compter les salles et étudiants par étage
                    $statsEtageStmt = $pdo->prepare("
                        SELECT 
                            COUNT(DISTINCT s.id) as nb_salles,
                            COALESCE(SUM(s.capacite), 0) as capacite_totale,
                            COUNT(es.id_etudiant) as nb_etudiants
                        FROM salles s
                        LEFT JOIN etudiants_salles es ON s.id = es.id_salle
                        WHERE s.id_etage = :etage_id
                    ");
                    $statsEtageStmt->execute(['etage_id' => $etage['id']]);
                    $stats = $statsEtageStmt->fetch();
                    
                    echo '<tr>';
                    echo '<td>' . $etage['id'] . '</td>';
                    echo '<td><strong>' . htmlspecialchars($etage['nom']) . '</strong></td>';
                    echo '<td>' . $etage['numero'] . '</td>';
                    echo '<td>' . $etage['superficie'] . ' m²</td>';
                    echo '<td>' . $stats['nb_salles'] . ' salle(s)</td>';
                    echo '<td>' . $stats['capacite_totale'] . ' places</td>';
                    echo '<td>' . $stats['nb_etudiants'] . ' étudiant(s)</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="info">Aucun étage trouvé</div>';
            }
            echo '</div>';
            
            // === ONGLET STATISTIQUES ===
            echo '<div id="stats" class="tab-content" style="display: none;">';
            echo '<h2>📊 Statistiques générales</h2>';
            
            // Calculs statistiques
            $totalSalles = $pdo->query("SELECT COUNT(*) as total FROM salles")->fetch()['total'];
            $totalEtages = $pdo->query("SELECT COUNT(*) as total FROM etage")->fetch()['total'];
            $capaciteTotale = $pdo->query("SELECT SUM(capacite) as total FROM salles")->fetch()['total'];
            $totalEtudiants = $pdo->query("SELECT COUNT(*) as total FROM etudiants_salles")->fetch()['total'];
            $capaciteMoyenne = $totalSalles > 0 ? round($capaciteTotale / $totalSalles, 1) : 0;
            $superficieTotale = $pdo->query("SELECT SUM(superficie) as total FROM etage")->fetch()['total'];
            
            echo '<div class="stats-grid">';
            echo '<div class="stat-card">';
            echo '<h3>🏢 Salles totales</h3>';
            echo '<div class="stat-number">' . $totalSalles . '</div>';
            echo '</div>';
            
            echo '<div class="stat-card">';
            echo '<h3>🏗️ Étages</h3>';
            echo '<div class="stat-number">' . $totalEtages . '</div>';
            echo '</div>';
            
            echo '<div class="stat-card">';
            echo '<h3>👥 Capacité totale</h3>';
            echo '<div class="stat-number">' . $capaciteTotale . '</div>';
            echo '</div>';
            
            echo '<div class="stat-card">';
            echo '<h3>📏 Superficie totale</h3>';
            echo '<div class="stat-number">' . $superficieTotale . ' m²</div>';
            echo '</div>';
            
            echo '<div class="stat-card">';
            echo '<h3>📊 Capacité moyenne</h3>';
            echo '<div class="stat-number">' . $capaciteMoyenne . '</div>';
            echo '</div>';
            
            echo '<div class="stat-card">';
            echo '<h3>👨‍🎓 Étudiants assignés</h3>';
            echo '<div class="stat-number">' . $totalEtudiants . '</div>';
            echo '</div>';
            echo '</div>';
            
            // Plus grande et plus petite salle
            $plusGrandeSalle = $pdo->query("SELECT nom, capacite FROM salles ORDER BY capacite DESC LIMIT 1")->fetch();
            $plusPetiteSalle = $pdo->query("SELECT nom, capacite FROM salles ORDER BY capacite ASC LIMIT 1")->fetch();
            
            echo '<div class="info">';
            echo '<strong>🏆 Plus grande salle :</strong> ' . htmlspecialchars($plusGrandeSalle['nom']) . ' (' . $plusGrandeSalle['capacite'] . ' places)<br>';
            echo '<strong>🏠 Plus petite salle :</strong> ' . htmlspecialchars($plusPetiteSalle['nom']) . ' (' . $plusPetiteSalle['capacite'] . ' places)<br>';
            echo '<strong>📊 Taux d\'occupation global :</strong> ' . ($capaciteTotale > 0 ? round(($totalEtudiants / $capaciteTotale) * 100, 1) : 0) . '%';
            echo '</div>';
            echo '</div>';
            
            // === ONGLET VUE COMPLÈTE ===
            echo '<div id="joined" class="tab-content" style="display: none;">';
            echo '<h2>🔗 Vue complète (Salles + Étages + Étudiants)</h2>';
            
            $joinedStmt = $pdo->query("
                SELECT s.id, s.nom as salle_nom, s.capacite, 
                       e.nom as etage_nom, e.numero as etage_numero, e.superficie,
                       COUNT(es.id_etudiant) as nb_etudiants
                FROM salles s 
                JOIN etage e ON s.id_etage = e.id 
                LEFT JOIN etudiants_salles es ON s.id = es.id_salle
                GROUP BY s.id
                ORDER BY e.numero, s.nom
            ");
            $joinedData = $joinedStmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($joinedData) {
                echo '<table>';
                echo '<thead><tr><th>ID Salle</th><th>Nom Salle</th><th>Capacité</th><th>Étudiants</th><th>Étage</th><th>Numéro</th><th>Superficie</th><th>Taux</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($joinedData as $row) {
                    $taux = $row['capacite'] > 0 ? round(($row['nb_etudiants'] / $row['capacite']) * 100, 1) : 0;
                    
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td><strong>' . htmlspecialchars($row['salle_nom']) . '</strong></td>';
                    echo '<td>' . $row['capacite'] . ' places</td>';
                    echo '<td>' . $row['nb_etudiants'] . ' étudiant(s)</td>';
                    echo '<td>' . htmlspecialchars($row['etage_nom']) . '</td>';
                    echo '<td>' . $row['etage_numero'] . '</td>';
                    echo '<td>' . $row['superficie'] . ' m²</td>';
                    echo '<td>' . $taux . '%</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="info">Aucune donnée trouvée</div>';
            }
            echo '</div>';
            
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