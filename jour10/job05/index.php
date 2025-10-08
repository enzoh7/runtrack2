<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Job 05 - Supprimer un étudiant</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: 
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: 
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid 
        th { background-color: 
        tr:nth-child(even) { background-color: 
        .delete-btn { background: 
        .delete-btn:hover { background: 
        .confirm-container { margin: 20px 0; padding: 25px; background: 
        button { padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin: 5px; }
        .btn-danger { background: 
        .btn-danger:hover { background: 
        .btn-secondary { background: 
        .btn-secondary:hover { background: 
        .success { background: 
        .error { background: 
    </style>
</head>
<body>
    <div class="container">
        <h1>🗑️ Job 05 - Supprimer un étudiant</h1>
        
        <?php
        $host = 'localhost';
        $dbname = 'jour09';
        $username = 'root';
        $password = '';
        
        $message = '';
        $etudiantASupprimer = null;
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmer_suppression'])) {
                $id = $_POST['id'];
    
                $deleteStmt = $pdo->prepare("DELETE FROM etudiants WHERE id = :id");
                $result = $deleteStmt->execute(['id' => $id]);
                
                if ($result) {
                    $message = '<div class="success"><strong>✅ Suppression réussie !</strong><br>L\'étudiant a été supprimé de la base de données.</div>';
                } else {
                    $message = '<div class="error"><strong>❌ Erreur lors de la suppression</strong></div>';
                }
            }
            if (isset($_GET['id']) && !isset($_POST['confirmer_suppression'])) {
                $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = :id");
                $stmt->execute(['id' => $_GET['id']]);
                $etudiantASupprimer = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$etudiantASupprimer) {
                    $message = '<div class="error"><strong>❌ Étudiant introuvable</strong></div>';
                }
            }
            
        } catch (PDOException $e) {
            $message = '<div class="error"><strong>❌ Erreur de base de données :</strong><br>' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        
        echo $message;
        if ($etudiantASupprimer) {
            ?>
            <div class="confirm-container">
                <h2>⚠️ Confirmation de suppression</h2>
                <p><strong>Attention !</strong> Vous êtes sur le point de supprimer définitivement cet étudiant :</p>
                
                <ul>
                    <li><strong>ID :</strong> <?php echo htmlspecialchars($etudiantASupprimer['id']); ?></li>
                    <li><strong>Nom complet :</strong> <?php echo htmlspecialchars($etudiantASupprimer['prenom'] . ' ' . $etudiantASupprimer['nom']); ?></li>
                    <li><strong>Email :</strong> <?php echo htmlspecialchars($etudiantASupprimer['email']); ?></li>
                    <li><strong>Date de naissance :</strong> <?php echo htmlspecialchars($etudiantASupprimer['naissance']); ?></li>
                </ul>
                
                <p style="color: 
                
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="id" value="<?php echo $etudiantASupprimer['id']; ?>">
                    <button type="submit" name="confirmer_suppression" class="btn-danger" onclick="return confirm('Êtes-vous vraiment sûr de vouloir supprimer cet étudiant ?')">
                        🗑️ Confirmer la suppression
                    </button>
                </form>
                
                <a href="?" class="btn-secondary" style="text-decoration: none; padding: 12px 24px; display: inline-block;">
                    ↩️ Annuler
                </a>
            </div>
            <?php
        }
        try {
            $stmt = $pdo->query("SELECT * FROM etudiants ORDER BY nom, prenom");
            $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($etudiants) {
                echo '<h2>👥 Liste des étudiants</h2>';
                echo '<p style="color: 
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
                    echo '<td><a href="?id=' . $etudiant['id'] . '" class="delete-btn">🗑️ Supprimer</a></td>';
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

