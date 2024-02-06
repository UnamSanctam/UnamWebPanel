<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
$csp_nonce = base64_encode(random_bytes(16));
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-{$csp_nonce}'; style-src 'self'; img-src 'self' data:; object-src 'none'; frame-src 'none'; child-src 'none'; worker-src 'none'; media-src 'none'; manifest-src 'none'; base-uri 'none'; form-action 'none';");
header("Feature-Policy: geolocation 'none'; microphone 'none'; camera 'none'");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
header("Referrer-Policy: no-referrer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header('X-Robots-Tag: noindex, nofollow');
header("Cross-Origin-Resource-Policy: same-origin");
require_once dirname(__DIR__).'/__UNAM_LIB/unam_lib.php';
require_once dirname(__DIR__).'/config.php';
require_once 'db.php';

class base extends unam_lib {
    function logout() {
        unset($_SESSION['HTTP_USER_AGENT']);
        unset($_SESSION['logged_in']);
        header("Location: login.php");
        die();
    }
}