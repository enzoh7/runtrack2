<?php
session_start();
if (!isset($_SESSION['morpion'])) {
    $_SESSION['morpion'] = array_fill(0, 3, array_fill(0, 3, '-'));
    $_SESSION['joueur'] = 'X';
    $_SESSION['fini'] = false;
}
if (isset($_POST['reset'])) {
    $_SESSION['morpion'] = array_fill(0, 3, array_fill(0, 3, '-'));
    $_SESSION['joueur'] = 'X';
    $_SESSION['fini'] = false;
}
if (isset($_POST['case']) && !$_SESSION['fini']) {
    $pos = explode('-', $_POST['case']);
    $i = $pos[0];
    $j = $pos[1];
    if ($_SESSION['morpion'][$i][$j] == '-') {
        $_SESSION['morpion'][$i][$j] = $_SESSION['joueur'];
        // Vérif victoire
        $g = $_SESSION['joueur'];
        $win = false;
        for ($k=0;$k<3;$k++) {
            if ($_SESSION['morpion'][$k][0]==$g && $_SESSION['morpion'][$k][1]==$g && $_SESSION['morpion'][$k][2]==$g) $win=true;
            if ($_SESSION['morpion'][0][$k]==$g && $_SESSION['morpion'][1][$k]==$g && $_SESSION['morpion'][2][$k]==$g) $win=true;
        }
        if ($_SESSION['morpion'][0][0]==$g && $_SESSION['morpion'][1][1]==$g && $_SESSION['morpion'][2][2]==$g) $win=true;
        if ($_SESSION['morpion'][0][2]==$g && $_SESSION['morpion'][1][1]==$g && $_SESSION['morpion'][2][0]==$g) $win=true;
        if ($win) {
            echo $g." a gagné";
            $_SESSION['fini'] = true;
        } else {
            // Match nul ?
            $full = true;
            for ($x=0;$x<3;$x++) for ($y=0;$y<3;$y++) if ($_SESSION['morpion'][$x][$y]=='-') $full=false;
            if ($full) {
                echo "Match nul";
                $_SESSION['fini'] = true;
            } else {
                $_SESSION['joueur'] = ($_SESSION['joueur']=='X')?'O':'X';
            }
        }
    }
}
echo '<form method="post">';
for ($i=0;$i<3;$i++) {
    for ($j=0;$j<3;$j++) {
        if ($_SESSION['morpion'][$i][$j]=='-') {
            echo '<button type="submit" name="case" value="'.$i.'-'.$j.'">-</button>';
        } else {
            echo $_SESSION['morpion'][$i][$j];
        }
    }
    echo '<br>';
}
echo '<button type="submit" name="reset">Réinitialiser la partie</button>';
echo '</form>';
?>
