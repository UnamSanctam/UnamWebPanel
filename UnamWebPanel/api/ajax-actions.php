<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/assets/php/security.php';

function checkJSON($input) {
    json_decode($input, true);
    if(json_last_error() != JSON_ERROR_NONE) {
        return false;
    }
    return true;
}

if(!empty(getParam('action'))) {
    switch (getParam('action')) {
        case 'config-add':
            $base->unam_checkCondition(!checkJSON($_POST['data']), "{$larr['invalid']} JSON.");
            $base->unam_dbInsert(getConn(), 'configs', ['cf_name'=>getParam('name'), 'cf_data'=>$_POST['data']]);
            $base->unam_echoSuccess($larr['status_added']);
            break;
        case 'config-update':
            $base->unam_checkCondition(!checkJSON($_POST['data']), "{$larr['invalid']} JSON.");
            $base->unam_dbUpdate(getConn(), 'configs', ['cf_data'=>$_POST['data']], ['cf_configID'=>getParam('index')]);
            $base->unam_echoSuccess($larr['status_updated']);
            break;
        case 'config-remove':
            $base->unam_checkCondition(getParam('index') <= 2, $larr['cannot_remove_default_configuration']);
            $base->unam_dbDelete(getConn(), 'configs', ['cf_configID'=>getParam('index')]);
            $base->unam_echoSuccess($larr['status_removed']);
            break;
        case 'miner-config':
            $base->unam_dbUpdate(getConn(), 'miners', ['ms_config'=>getParam('config')], ['ms_minerID'=>getParam('index')]);
            $base->unam_echoSuccess($larr['status_updated']);
            break;
        case 'miner-remove':
            getConn()->exec('PRAGMA foreign_keys = ON;');
            $base->unam_dbDelete(getConn(), 'miners', ['ms_minerID'=>getParam('index')]);
            getConn()->exec('PRAGMA wal_checkpoint(TRUNCATE);VACUUM;PRAGMA OPTIMIZE;PRAGMA foreign_keys = OFF;');
            $base->unam_echoSuccess($larr['status_removed']);
            break;
        case 'miner-clean':
            $base->unam_checkCondition(!preg_match("/^\d+$/", getParam('amount')) || getParam('amount') < 1, "{$larr['invalid_input']}.");
            getConn()->exec('PRAGMA foreign_keys = ON;');
            $minerclean = getConn()->prepare('DELETE FROM miners WHERE ms_lastConnection < ?;');
            $minerclean->execute([date('Y-m-d H:i:s', strtotime('-'.getParam('amount').' day'))]);
            getConn()->exec('PRAGMA wal_checkpoint(TRUNCATE);VACUUM;PRAGMA OPTIMIZE;PRAGMA foreign_keys = OFF;');
            $base->unam_echoSuccess("{$larr['success']}!");
            break;
        case 'miner-offline':
            $_SESSION['hide_offline_miners'] = !($_SESSION['hide_offline_miners'] ?? false);
            $base->unam_echoSuccess("{$larr['success']}!");
            break;
        case 'miner-history':
            $base->unam_checkCondition(!getParam('index'), "{$larr['invalid']} ID.");
            $hashratecon = getConn()->prepare("SELECT hr_algorithm, SUM(hr_hashrate) AS hashrate, hr_date FROM hashrate WHERE hr_minerID = ? GROUP BY hr_date, hr_algorithm ORDER BY hr_date");
            $hashratecon->execute([getParam('index')]);
            $hashrate = $hashratecon->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($hashrate)) {
                $hashratearr = [];
                foreach ($hashrate as $value) {
                    $hashratearr[] = ['x' => date('Y-m-d H:i:s', $value['hr_date']), 'y' => $base->unam_sanitize($value['hashrate'])];
                }
                $base->unam_echoSuccess("<canvas class='hook-chart' data-chart-type='hashrate' data-chart-config='".json_encode(['type'=>'bar', 'data'=>['datasets'=>[['label'=>$hashrate[0]['hr_algorithm'], 'data'=>$hashratearr, 'fill'=>true]]], 'options'=>['responsive'=>true, 'scales'=>['x'=>['type'=>'time', 'max'=>date('Y-m-d H:i:00'), 'min'=>$hashratearr[0]['x'], 'time'=>['minUnit'=>'minute']], 'y'=>['min'=>0]]]])."'></canvas>");
            } else {
                $base->unam_echoFailure($larr['no_hashrate_for_miner']);
            }
            break;
        case 'ipblock-add':
            $ip = getParam('ip') ?: getParam('index');
            $base->unam_checkCondition(!filter_var($ip, FILTER_VALIDATE_IP), "{$larr['invalid']} IP.");
            $base->unam_checkCondition(!empty($base->unam_dbSelect(getConn(), 'ipblocking', '*', ['ipb_ip'=>$ip])), $larr['ip_already_blocked']);
            $base->unam_dbInsert(getConn(), 'ipblocking', ['ipb_ip'=>$ip, 'ipb_note'=>getParam('note'), 'ipb_datetime'=>$currentDate]);
            $base->unam_echoSuccess($larr['status_added']);
            break;
        case 'ipblock-remove':
            $base->unam_checkCondition(!getParam('index'), "{$larr['invalid']} ID.");
            $base->unam_dbDelete(getConn(), 'ipblocking', ['ipb_blockID'=>getParam('index')]);
            $base->unam_echoSuccess($larr['status_removed']);
            break;
    }
}