<?php
if (isset($_POST['deco'])) {
    setcookie('prenom', '', time() - 3600);
    $_COOKIE['prenom'] = '';
}
if (isset($_POST['connexion']) && isset($_POST['prenom']) && $_POST['prenom'] !== '') {
    setcookie('prenom', $_POST['prenom'], time() + 3600);
    $_COOKIE['prenom'] = $_POST['prenom'];
}
if (isset($_COOKIE['prenom']) && $_COOKIE['prenom'] !== '') {
    echo "Bonjour ".htmlspecialchars($_COOKIE['prenom'])." !";
    echo '<form method="post"><button type="submit" name="deco">Déconnexion</button></form>';
} else {
    echo '<form method="post">';
    echo '<input type="text" name="prenom" placeholder="Prénom">';
    echo '<button type="submit" name="connexion">Connexion</button>';
    echo '</form>';
}
