<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 01 - Connexion et affichage étudiants</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: 
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: 
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid 
        th { background-color: 
        tr:nth-child(even) { background-color: 
        tr:hover { background-color: 
        .error { background: 
        .success { background: 
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Job 01 - Liste des étudiants</h1>
        
        <?php
        $host = 'localhost';
        $dbname = 'jour09';
        $username = 'root';
        $password = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->query("SELECT * FROM etudiants");
            $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($etudiants) {
                echo '<div class="success">';
                echo '<strong>✅ Connexion réussie !</strong> ' . count($etudiants) . ' étudiant(s) trouvé(s).';
                echo '</div>';
                
                echo '<table>';
                echo '<thead><tr><th>ID</th><th>Prénom</th><th>Nom</th><th>Naissance</th><th>Sexe</th><th>Email</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($etudiants as $etudiant) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($etudiant['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['prenom']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['nom']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['naissance']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['sexe']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['email']) . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="error">Aucun étudiant trouvé dans la base de données.</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="error">';
            echo '<strong>❌ Erreur de connexion :</strong><br>';
            echo 'Message : ' . htmlspecialchars($e->getMessage()) . '<br><br>';
            echo '<strong>Vérifications :</strong><br>';
            echo '• WAMP/XAMPP est démarré<br>';
            echo '• La base "jour09" existe<br>';
            echo '• La table "etudiants" est créée';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>

