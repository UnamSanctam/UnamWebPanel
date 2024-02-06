<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/assets/php/datatables.php';
require_once dirname(__DIR__).'/class/class.ssp.php';

if($loggedin && !empty([$datatables['tables'][getParam('tableid')]]) && $datatables['tables'][getParam('tableid')]['enabled']){
    $table = $datatables['tables'][getParam('tableid')];

    $where = [];
    if(getParam('tableid') == 'miner-table' && isset($_SESSION['hide_offline_miners']) && $_SESSION['hide_offline_miners']) {
        $where[] = [
            'db_column' => 'ms_lastConnection',
            'db_operation' => '>',
            'db_value' => date('Y-m-d H:i:s', strtotime('-3 minutes'))
        ];
    }

    echo json_encode(SSP::simple($_POST, getConn(), $table['db_table'], $table['db_primary_key'], $table['columns'], $where));
} else {
    echo json_encode([]);
}