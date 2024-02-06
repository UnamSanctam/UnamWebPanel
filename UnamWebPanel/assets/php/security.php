<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once 'session-header.php';

if(!$loggedin) {
    $base->logout();
}

if (!empty($_SESSION['HTTP_USER_AGENT'])) {
    if ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
        $base->logout();
    }
} else {
    $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
}