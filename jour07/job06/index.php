<?php
function leetSpeak($str) {
    $leet = [
        'A' => '4', 'a' => '4',
        'B' => '8', 'b' => '8',
        'E' => '3', 'e' => '3',
        'G' => '6', 'g' => '6',
        'L' => '1', 'l' => '1',
        'S' => '5', 's' => '5',
        'T' => '7', 't' => '7'
    ];
    $result = '';
    for ($i = 0; isset($str[$i]); $i++) {
        $c = $str[$i];
        $result .= isset($leet[$c]) ? $leet[$c] : $c;
    }
    return $result;
}
echo leetSpeak("Salut LaPlateforme!");
