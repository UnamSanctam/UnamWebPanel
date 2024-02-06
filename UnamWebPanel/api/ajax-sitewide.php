<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once(dirname(__DIR__) . '/assets/php/session-header.php');

switch(getParam('action')){
    case 'lang-change':
        $base->unam_checkCondition(!in_array(getParam('newlangID'), array_keys($config['languages'])), 'Language ID invalid.');
        $_SESSION['lang'] = getParam('newlangID');
        $base->unam_echoSuccess();
        break;
}