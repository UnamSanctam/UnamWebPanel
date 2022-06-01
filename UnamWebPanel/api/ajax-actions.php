<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/security.php';

if(!empty(getParam('method'))) {
    switch (getParam('method')) {
        case 'config-add':
            $base->unam_checkCondition(!checkJSON(getParam('data')), "{$larr['Invalid']} JSON.");
            $base->tableModify(getConn(), 'insert', 'configs', 'cf', ['name', 'data'], []);
            $base->unam_echoSuccess("{$larr['Configuration']} {$larr['has_been_added']}.");
            break;
        case 'config-update':
            $base->unam_checkCondition(!checkJSON(getParam('data')), "{$larr['Invalid']} JSON.");
            $base->tableModify(getConn(), 'update', 'configs', 'cf', ['data'], ['configID']);
            $base->unam_echoSuccess("{$larr['Configuration']} {$larr['has_been_updated']}.");
            break;
        case 'config-remove':
            $base->unam_checkCondition(getParam('index') == 1 || getParam('index') == 2, $larr['cannot_remove_default']);
            $base->tableModify(getConn(), 'delete', 'configs', 'cf', [], ['configID']);
            $base->unam_echoSuccess("{$larr['Configuration']} {$larr['has_been_removed']}.");
            break;
        case 'miner-config':
            $base->tableModify(getConn(), 'update', 'miners', 'ms', ['config'], ['minerID']);
            $base->unam_echoSuccess("{$larr['Miner']} {$larr['has_been_updated']}.");
            break;
        case 'miner-remove':
            $base->tableModify(getConn(), 'delete', 'miners', 'ms', [], ['minerID']);
            $base->unam_echoSuccess("{$larr['Miner']} {$larr['has_been_removed']}.");
            break;
        case 'miner-clean':
            $base->unam_checkCondition(!preg_match("/^\d+$/", getParam('amount')) || getParam('amount') < 1, "{$larr['invalid_input']}.");
            $base->unam_dbDelete(getConn(), 'miners', ['$CUSTOM'=>"ms_lastConnection < datetime('now', '-".getParam('amount')." day')"]);
            $base->unam_echoSuccess("{$larr['Success']}!");
            break;
    }
}