<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once(dirname(__DIR__) . '/assets/php/session-header.php');

switch(getParam('method')){
    case 'lang-change':
        $base->unam_checkCondition(!in_array(getParam('newlangID'), $config['languages']), 'Language ID invalid.');
        $_SESSION['lang'] = getParam('newlangID');
        echo json_encode(['response' => 'success']);
        break;
}