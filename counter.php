<?php

$fp = fopen('./counter.txt', 'c+');

if ($fp && flock($fp, LOCK_SH)) {
    $counter = fgets($fp) + 1;
    fseek($fp, 0);
    fputs($fp, $counter);
    fclose($fp);
}
