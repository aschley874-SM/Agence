<?php
function formatLikes($n)
 {
    if ($n >= 1000000) {
        return round($n / 1000000, 1) . 'M';
    } elseif ($n >= 1000) {
        return round($n / 1000, 1) . 'k';
    }
    return $n;
}