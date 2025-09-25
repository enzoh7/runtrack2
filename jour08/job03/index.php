<?php
session_start();
if (isset($_POST['reset'])) {
    $_SESSION['prenoms'] = [];
} else if (isset($_POST['prenom']) && $_POST['prenom'] !== '') {
    if (!isset($_SESSION['prenoms'])) {
        $_SESSION['prenoms'] = [];
    }
    $_SESSION['prenoms'][] = $_POST['prenom'];
}
echo "<ul>";
if (isset($_SESSION['prenoms'])) {
    foreach ($_SESSION['prenoms'] as $p) {
        echo "<li>".htmlspecialchars($p)."</li>";
    }
}
echo "</ul>";
?>
<form method="post">
    <input type="text" name="prenom" placeholder="Prénom">
    <button type="submit">Ajouter</button>
    <button type="submit" name="reset">reset</button>
</form>
