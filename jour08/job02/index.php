<?php
if (isset($_POST['reset'])) {
    setcookie('nbvisites', 0, time() + 3600);
    $_COOKIE['nbvisites'] = 0;
} else {
    if (!isset($_COOKIE['nbvisites'])) {
        setcookie('nbvisites', 1, time() + 3600);
        $_COOKIE['nbvisites'] = 1;
    } else {
        $nb = $_COOKIE['nbvisites'] + 1;
        setcookie('nbvisites', $nb, time() + 3600);
        $_COOKIE['nbvisites'] = $nb;
    }
}
echo "Nombre de visites (cookie) : " . $_COOKIE['nbvisites'];
?>
<form method="post">
    <button type="submit" name="reset">reset</button>
</form>
