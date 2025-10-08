<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 03 - Ajouter un étudiant</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid 
        th { background-color: 
        form { margin: 20px 0; padding: 20px; background: 
        input, select { padding: 8px; margin: 5px; width: 200px; }
        button { padding: 10px 20px; background: 
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Job 03 - Ajouter un étudiant</h1>
    
    <?php
    
    $host = 'localhost';
    $dbname = 'jour09';
    $username = 'root';
    $password = '';
    
    $message = '';
    
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
        require_once '../sql_helper.php';
        
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter'])) {
            $prenom = trim($_POST['prenom']);
            $nom = trim($_POST['nom']);
            $naissance = $_POST['naissance'];
            $sexe = $_POST['sexe'];
            $email = trim($_POST['email']);
            
            
            if (!empty($prenom) && !empty($nom) && !empty($naissance) && !empty($sexe) && !empty($email)) {
                
                $emailCount = getCount($pdo, 'check_email_exists', [$email]);
                
                if ($emailCount == 0) {
                    
                    $insertStmt = $pdo->prepare(loadSQLQuery('insert_student'));
                    $result = $insertStmt->execute([$prenom, $nom, $naissance, $sexe, $email]);
                    
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

