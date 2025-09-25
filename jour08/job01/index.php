<?php
session_start();
if (isset($_POST['reset'])) {
    $_SESSION['nbvisites'] = 0;
} else {
    if (!isset($_SESSION['nbvisites'])) {
        $_SESSION['nbvisites'] = 1;
    } else {
        $_SESSION['nbvisites']++;
    }
}
echo "Nombre de visites (session) : " . $_SESSION['nbvisites'];
?>
<form method="post">
    <button type="submit" name="reset">reset</button>
</form>
