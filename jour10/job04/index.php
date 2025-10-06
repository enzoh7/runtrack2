<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 04 - Modifier un étudiant</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #007cba; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .form-container { margin: 20px 0; padding: 25px; background: #fff3cd; border-radius: 8px; border: 2px solid #ffc107; }
        input, select { padding: 10px; margin: 5px; width: 250px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 12px 24px; background: #ffc107; color: #212529; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #e0a800; }
        .edit-btn { background: #17a2b8; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-left: 4px solid #28a745; margin: 20px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .form-group { margin: 15px 0; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Job 04 - Modifier un étudiant</h1>
        
        <?php
        $host = 'localhost';
        $dbname = 'jour09';
        $username = 'root';
        $password = '';
        
        $message = '';
        $etudiantAModifier = null;
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Traitement de la modification
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modifier'])) {
                $id = $_POST['id'];
                $prenom = trim($_POST['prenom']);
                $nom = trim($_POST['nom']);
                $naissance = $_POST['naissance'];
                $sexe = $_POST['sexe'];
                $email = trim($_POST['email']);
                
                if (!empty($prenom) && !empty($nom) && !empty($naissance) && !empty($sexe) && !empty($email)) {
                    // Vérifier que l'email n'est pas utilisé par un autre étudiant
                    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM etudiants WHERE email = :email AND id != :id");
                    $checkStmt->execute(['email' => $email, 'id' => $id]);
                    
                    if ($checkStmt->fetchColumn() == 0) {
                        $updateStmt = $pdo->prepare("UPDATE etudiants SET prenom = :prenom, nom = :nom, naissance = :naissance, sexe = :sexe, email = :email WHERE id = :id");
                        $result = $updateStmt->execute([
                            'prenom' => $prenom,
                            'nom' => $nom,
                            'naissance' => $naissance,
                            'sexe' => $sexe,
                            'email' => $email,
                            'id' => $id
                        ]);
                        
                        if ($result) {
                            $message = '<div class="success"><strong>✅ Modification réussie !</strong><br>L\'étudiant a été mis à jour.</div>';
                        } else {
                            $message = '<div class="error"><strong>❌ Erreur lors de la modification</strong></div>';
                        }
                    } else {
                        $message = '<div class="error"><strong>⚠️ Email déjà utilisé par un autre étudiant</strong></div>';
                    }
                } else {
                    $message = '<div class="error"><strong>📝 Tous les champs sont obligatoires</strong></div>';
                }
            }
            
            // Récupération de l'étudiant à modifier
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = :id");
                $stmt->execute(['id' => $_GET['id']]);
                $etudiantAModifier = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            
        } catch (PDOException $e) {
            $message = '<div class="error"><strong>❌ Erreur de base de données :</strong><br>' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        
        echo $message;
        
        // Formulaire de modification si un étudiant est sélectionné
        if ($etudiantAModifier) {
            ?>
            <form method="POST" class="form-container">
                <h2>📝 Modifier l'étudiant #<?php echo $etudiantAModifier['id']; ?></h2>
                <input type="hidden" name="id" value="<?php echo $etudiantAModifier['id']; ?>">
                
                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" name="prenom" id="prenom" required value="<?php echo htmlspecialchars($etudiantAModifier['prenom']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" id="nom" required value="<?php echo htmlspecialchars($etudiantAModifier['nom']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="naissance">Date de naissance :</label>
                    <input type="date" name="naissance" id="naissance" required value="<?php echo htmlspecialchars($etudiantAModifier['naissance']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="sexe">Sexe :</label>
                    <select name="sexe" id="sexe" required>
                        <option value="Homme" <?php echo ($etudiantAModifier['sexe'] == 'Homme') ? 'selected' : ''; ?>>Homme</option>
                        <option value="Femme" <?php echo ($etudiantAModifier['sexe'] == 'Femme') ? 'selected' : ''; ?>>Femme</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($etudiantAModifier['email']); ?>">
                </div>
                
                <div class="form-group">
                    <button type="submit" name="modifier">💾 Sauvegarder les modifications</button>
                    <a href="?" style="margin-left: 10px; padding: 12px 24px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">↩️ Retour</a>
                </div>
            </form>
            <?php
        }
        
        // Liste des étudiants
        try {
            $stmt = $pdo->query("SELECT * FROM etudiants ORDER BY nom, prenom");
            $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($etudiants) {
                echo '<h2>👥 Liste des étudiants</h2>';
                echo '<table>';
                echo '<thead><tr><th>ID</th><th>Prénom</th><th>Nom</th><th>Naissance</th><th>Sexe</th><th>Email</th><th>Action</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($etudiants as $etudiant) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($etudiant['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['prenom']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['nom']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['naissance']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['sexe']) . '</td>';
                    echo '<td>' . htmlspecialchars($etudiant['email']) . '</td>';
                    echo '<td><a href="?id=' . $etudiant['id'] . '" class="edit-btn">✏️ Modifier</a></td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="error"><strong>📝 Aucun étudiant dans la base</strong></div>';
            }
        } catch (PDOException $e) {
            echo '<div class="error"><strong>❌ Erreur :</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>
</body>
</html>