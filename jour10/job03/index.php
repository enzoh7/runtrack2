<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 03 - Ajouter un étudiant</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin: 20px 0; padding: 20px; background: #f9f9f9; border-radius: 5px; }
        input, select { padding: 8px; margin: 5px; width: 200px; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Job 03 - Ajouter un étudiant</h1>
    
    <?php
    // Configuration de la base de données
    $host = 'localhost';
    $dbname = 'jour09';
    $username = 'root';
    $password = '';
    
    $message = '';
    
    try {
        // Connexion à la base de données avec PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Traitement du formulaire d'ajout
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter'])) {
            $prenom = trim($_POST['prenom']);
            $nom = trim($_POST['nom']);
            $naissance = $_POST['naissance'];
            $sexe = $_POST['sexe'];
            $email = trim($_POST['email']);
            
            // Validation simple
            if (!empty($prenom) && !empty($nom) && !empty($naissance) && !empty($sexe) && !empty($email)) {
                // Vérifier si l'email existe déjà
                $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM etudiants WHERE email = :email");
                $checkStmt->execute(['email' => $email]);
                
                if ($checkStmt->fetchColumn() == 0) {
                    // Insérer le nouvel étudiant
                    $insertStmt = $pdo->prepare("INSERT INTO etudiants (prenom, nom, naissance, sexe, email) VALUES (:prenom, :nom, :naissance, :sexe, :email)");
                    $result = $insertStmt->execute([
                        'prenom' => $prenom,
                        'nom' => $nom,
                        'naissance' => $naissance,
                        'sexe' => $sexe,
                        'email' => $email
                    ]);
                    
                    if ($result) {
                        $message = '<p class="success">Étudiant ajouté avec succès !</p>';
                    } else {
                        $message = '<p class="error">Erreur lors de l\'ajout de l\'étudiant.</p>';
                    }
                } else {
                    $message = '<p class="error">Cet email existe déjà dans la base de données.</p>';
                }
            } else {
                $message = '<p class="error">Tous les champs sont obligatoires.</p>';
            }
        }
        
    } catch (PDOException $e) {
        $message = '<p class="error">Erreur de connexion : ' . $e->getMessage() . '</p>';
    }
    
    echo $message;
    ?>
    
    <form method="POST">
        <h2>Ajouter un nouvel étudiant</h2>
        <div>
            <label>Prénom :</label><br>
            <input type="text" name="prenom" required>
        </div>
        <div>
            <label>Nom :</label><br>
            <input type="text" name="nom" required>
        </div>
        <div>
            <label>Date de naissance :</label><br>
            <input type="date" name="naissance" required>
        </div>
        <div>
            <label>Sexe :</label><br>
            <select name="sexe" required>
                <option value="">-- Choisir --</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>
        </div>
        <div>
            <label>Email :</label><br>
            <input type="email" name="email" required>
        </div>
        <div>
            <button type="submit" name="ajouter">Ajouter l'étudiant</button>
        </div>
    </form>
    
    <h2>Liste des étudiants</h2>
    
    <?php
    try {
        // Afficher tous les étudiants
        $stmt = $pdo->query("SELECT * FROM etudiants ORDER BY nom, prenom");
        $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($etudiants) {
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
            echo '<p>Aucun étudiant dans la base de données.</p>';
        }
        
    } catch (PDOException $e) {
        echo '<p class="error">Erreur lors de la récupération des données : ' . $e->getMessage() . '</p>';
    }
    ?>
</body>
</html>