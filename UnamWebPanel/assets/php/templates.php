<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once 'security.php';

function unamMinerStatus($status){
    global $larr;
    switch($status){
        case 1:
            return "<span class='text-status-red'>{$larr['stopped']}</span>";
        case 2:
            return "<span class='text-status-green'>{$larr['mining']}</span>";
        case 3:
            return "<span class='text-status-green'>{$larr['mining']} ({$larr['idle']})</span>";
        case 4:
            return "<span class='text-status-yellow'>{$larr['paused']} ({$larr['stealth']} - {REASON})</span>";
        case 5:
            return "<span class='text-status-yellow'>{$larr['not_enough_free_vram']}</span>";
        case 6:
            return "<span class='text-status-yellow'>{$larr['starting']}</span>";
        case 7:
            return "<span class='text-status-red'>{$larr['error']}</span>";
        case -1:
            return "<span class='text-status-red'>{$larr['offline']}</span>";
        default:
            return "<span class='text-status-red'>{$larr['unknown']}</span>";
    }
}

function unamTimeFormat($timeline, $shortform) {
    $periods = [($shortform ? 'd' : 'day') => 86400, ($shortform ? 'h' : 'hour') => 3600, ($shortform ? 'm' : 'minute') => 60, ($shortform ? 's' : 'second') => 1];
    $ret = "";
    if($timeline) {
        foreach ($periods as $name => $seconds) {
            $num = floor($timeline / $seconds);
            $timeline -= ($num * $seconds);
            if ($num > 0) {
                $ret .= ($shortform ? "{$num}{$name} " : "{$num} {$name}" . (($num > 1) ? 's' : '') . ' ');
            }
        }
    }
    return trim($ret);
}

function unamFormatHashrate($num) {
    $num = $num ?: 0;
    $units = ['H/s', 'KH/s', 'MH/s', 'GH/s', 'TH/s', 'PH/s', 'EH/s'];
    for ($i = 0; $num >= 1000; $i++) {
        $num /= 1000;
    }
    return round($num, 1)." {$units[$i]}";
}