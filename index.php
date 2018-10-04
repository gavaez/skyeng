<?php

/**
 * @param string $n1
 * @param string $n2
 *
 * @return string|false
 */
function sum(string $n1, string $n2) {
    if (preg_match('#\D#', $n1 . $n2)) {
        return false;
    }

    $i = max(strlen($n1 = ltrim($n1, '0')), strlen($n2 = ltrim($n2, '0')));
    if (!$i) {
        return '0';
    }
    if (!$n1) {
        return $n2;
    }
    if (!$n2) {
        return $n1;
    }

    $n1 = str_pad($n1, $i, '0', STR_PAD_LEFT);
    $n2 = str_pad($n2, $i, '0', STR_PAD_LEFT);

    for ($d = 0; $i; $d = ($d - $n2[$i]) / 10) {
        $n2[--$i] = ($d += $n1[$i] + $n2[$i]) % 10;
    }

    return ltrim($d . $n2, '0');
}
