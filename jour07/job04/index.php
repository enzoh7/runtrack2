<?php
function calcule($a, $operation, $b) {
    if ($operation == '+') return $a + $b;
    if ($operation == '-') return $a - $b;
    if ($operation == '*') return $a * $b;
    if ($operation == '/') return $b != 0 ? $a / $b : 'Erreur : division par zéro';
    if ($operation == '%') return $b != 0 ? $a % $b : 'Erreur : division par zéro';
    return 'Opération inconnue';
}
echo calcule(5, '+', 3);
echo calcule(5, '/', 0);
