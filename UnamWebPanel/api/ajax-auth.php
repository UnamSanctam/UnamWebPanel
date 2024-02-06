<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/assets/php/session-header.php';

if(getParam('csrf_token') != $_SESSION['csrf_token']) {
    $base->unam_echoFailure('Invalid CSRF token.');
} else {
    $hostAddress = $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';
    $loginBlock = $base->unam_dbSelect(getConn(), 'loginblock', '*', ['lb_ip' => $hostAddress]);
    if (!empty($loginBlock) && ($loginBlock['lb_logintries'] >= $config['failedlogin_blocktries'] || $loginBlock['lb_logintries'] == 0) && (strtotime(date("Y-m-d H:i:s")) - strtotime($loginBlock['lb_datetime'])) / 60 < $loginBlock['lb_blocktime']) {
        $base->unam_echoFailure(sprintf($larr['login_blocked'], ($loginBlock['lb_blocktime'] - (int)((strtotime(date("Y-m-d H:i:s")) - strtotime($loginBlock['lb_datetime'])) / 60))));
    } else if (!empty(getParam('password'))) {
        if (getParam('password') === $config['password']) {
            $_SESSION['logged_in'] = password_hash($config['password'], PASSWORD_DEFAULT);
            $base->unam_dbDelete(getConn(), "loginblock", ['lb_ip' => $hostAddress]);
            $base->unam_echoSuccess();
        } else {
            if (empty($loginBlock) || ((strtotime(date("Y-m-d H:i:s")) - strtotime($loginBlock['lb_datetime'])) / 60 >= $loginBlock['lb_blocktime'])) {
                $base->unam_dbDelete(getConn(), "loginblock", ['lb_ip' => $hostAddress]);
                $base->unam_dbInsert(getConn(), 'loginblock', ['lb_ip' => $hostAddress, 'lb_logintries' => 1, 'lb_datetime' => date('Y-m-d H:i:s'), 'lb_blocktime' => $config['failedlogin_blocktime']]);
            } else {
                $base->unam_dbUpdate(getConn(), 'loginblock', ['lb_logintries' => $loginBlock['lb_logintries'] + 1], ['lb_ip' => $hostAddress]);
            }

            $base->unam_echoFailure('Wrong password!');
        }
    } else {
        $base->unam_echoFailure('Some required fields are missing.');
    }
}