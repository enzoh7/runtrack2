<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 02 - Recherche d'étudiants</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin: 20px 0; padding: 20px; background: #f9f9f9; border-radius: 5px; }
        input, select { padding: 8px; margin: 5px; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Job 02 - Recherche d'étudiants</h1>
    
    <form method="GET">
        <label>Rechercher par :</label>
        <select name="critere">
            <option value="prenom" <?php echo (isset($_GET['critere']) && $_GET['critere'] == 'prenom') ? 'selected' : ''; ?>>Prénom</option>
            <option value="nom" <?php echo (isset($_GET['critere']) && $_GET['critere'] == 'nom') ? 'selected' : ''; ?>>Nom</option>
            <option value="sexe" <?php echo (isset($_GET['critere']) && $_GET['critere'] == 'sexe') ? 'selected' : ''; ?>>Sexe</option>
        </select>
        <input type="text" name="valeur" placeholder="Valeur à rechercher" value="<?php echo isset($_GET['valeur']) ? htmlspecialchars($_GET['valeur']) : ''; ?>">
        <button type="submit">Rechercher</button>
        <a href="?"><button type="button">Afficher tous</button></a>
    </form>
    
    <?php
    // Configuration de la base de données
    $host = 'localhost';
    $dbname = 'jour09';
    $username = 'root';
    $password = '';
    
    try {
        // Connexion à la base de données avec PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Construction de la requête selon les critères
        if (isset($_GET['critere']) && isset($_GET['valeur']) && !empty($_GET['valeur'])) {
            $critere = $_GET['critere'];
            $valeur = $_GET['valeur'];
            
            // Sécurisation : vérification que le critère est valide
            $criteresValides = ['prenom', 'nom', 'sexe'];
            if (in_array($critere, $criteresValides)) {
                $sql = "SELECT * FROM etudiants WHERE $critere LIKE :valeur";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['valeur' => '%' . $valeur . '%']);
            } else {
                $stmt = $pdo->query("SELECT * FROM etudiants");
            }
        } else {
            // Afficher tous les étudiants si aucun critère
            $stmt = $pdo->query("SELECT * FROM etudiants");
        }
        
        $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($etudiants) {
            echo '<p>Nombre de résultats : ' . count($etudiants) . '</p>';
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
            echo '<p>Aucun étudiant trouvé pour cette recherche.</p>';
        }
        
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
    }
    ?>
</body>
</html>