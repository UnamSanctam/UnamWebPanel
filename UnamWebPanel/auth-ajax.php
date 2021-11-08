<?php
    /* Made by Unam Sanctam https://github.com/UnamSanctam */
    require_once('assets/php/session-header.php');
    $method = $base->unam_filterParameter('method');
    $csrf_token = $base->unam_filterParameter('csrf_token');

    if(!empty($method) && $csrf_token == $_SESSION['csrf_token']) {
        switch ($method) {
            case 'Login':
                $password = $base->unam_filterParameter('password');
                $loginBlock = $base->unam_dbSelect(getConn(), 'IPBlock', 'ipb_ip, ipb_logintries, ipb_datetime, ipb_blocktime', ['ipb_ip' => $hostaddress]);

                if (!empty($loginBlock) && ($loginBlock['ipb_logintries'] >= $config['failedlogin_blocktries'] || $loginBlock['ipb_logintries'] == 0) && (strtotime(date("Y-m-d H:i:s")) - strtotime($loginBlock['ipb_datetime'])) / 60 < $loginBlock['ipb_blocktime']) {
                    echo json_encode(['response' => 3, 'errormsg' => 'Too many unsuccessful login attempts, please wait ' . ($loginBlock['ipb_blocktime'] - (int)((strtotime(date("Y-m-d H:i:s")) - strtotime($loginBlock['ipb_datetime'])) / 60)) . ' minute(s) and try again.']);
                } else if (!empty($password)) {
                    if ($password === $config['password']) {

                        $_SESSION['logged_in'] = true;

                        $base->unam_dbDelete(getConn(), "IPBlock", ["ipb_ip" => $hostaddress]);

                        echo json_encode(['response' => 'success']);
                    } else {
                        if (empty($loginBlock) || (!empty($loginBlock) && (strtotime(date("Y-m-d H:i:s")) - strtotime($loginBlock['ipb_datetime'])) / 60 >= $loginBlock['ipb_blocktime'])) {
                            $base->unam_dbDelete(getConn(), "IPBlock", ["ipb_ip" => $hostaddress]);
                            $base->unam_dbInsert(getConn(), 'IPBlock', ['ipb_ip' => $hostaddress, 'ipb_logintries' => 1, 'ipb_datetime' => date('Y-m-d H:i:s'), 'ipb_blocktime' => $config['failedlogin_blocktime']]);
                        } else {
                            $base->unam_dbUpdate(getConn(), 'IPBlock', ['ipb_logintries' => $loginBlock['ipb_logintries'] + 1], ['ipb_ip' => $hostaddress]);
                        }

                        echo json_encode(['response' => 'failure', 'errormsg' => 'Wrong password!']);
                    }
                } else {
                    echo json_encode(['response' => 'failure', 'errormsg' => 'Some required fields are missing.']);
                }
                break;
        }
    }