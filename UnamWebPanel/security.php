<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once 'assets/php/session-header.php';

function logoutFunction(){
    global $base, $config;
    $base->unsetSession();
    if(isset($_SERVER['HTTP_UNAM_REQUEST_TYPE']) && $_SERVER['HTTP_UNAM_REQUEST_TYPE'] == 'AJAX'){
        echo json_encode(['sessionExpired'=>1]);
    }else{
        header("Location: login.php");
    }
    die();
}

if(!$loggedin)
{
    logoutFunction();
}

if (isset($_SESSION['HTTP_USER_AGENT']))
{
    if ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT'])
    {
        logoutFunction();
    }
}
else
{
    $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
}