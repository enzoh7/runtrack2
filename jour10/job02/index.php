<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 02 - Recherche d'étudiants</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid 
        th { background-color: 
        form { margin: 20px 0; padding: 20px; background: 
        input, select { padding: 8px; margin: 5px; }
        button { padding: 10px 20px; background: 
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
    
    $host = 'localhost';
    $dbname = 'jour09';
    $username = 'root';
    $password = '';
    
    try {
        
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
        require_once '../sql_helper.php';
        
        
        if (isset($_GET['critere']) && isset($_GET['valeur']) && !empty($_GET['valeur'])) {
            $critere = $_GET['critere'];
            $valeur = $_GET['valeur'];
            
            
            if (validateSearchCriteria($critere)) {
                $sql = loadSQLQuery('search_students_by_criteria', ['FIELD' => $critere]);
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['valeur' => '%' . $valeur . '%']);
            } else {
                $etudiants = getAllResults($pdo, 'select_all_students');
            }
        } else {
            
            $etudiants = getAllResults($pdo, 'select_all_students');
        }
        
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

