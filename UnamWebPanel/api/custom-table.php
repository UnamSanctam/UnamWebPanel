<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/assets/php/templates.php';
require_once dirname(__DIR__).'/class/class.ssp.php';
require_once dirname(__DIR__).'/assets/php/datatables.php';
require_once dirname(__DIR__).'/security.php';

function db_getPrefix(&$globalprefix, &$columnprefix){
    return ($columnprefix ?: ($globalprefix ?: ''));
}

function db_setFilters(&$options, $alias, $allowedfilters){
    $filters = getParam('filters');
    if(!empty($filters) && is_array($filters)){
        foreach($filters as $filter=>$val){
            if(!empty($val) && in_array($filter, $allowedfilters)){
                $options['db_where'][] = [
                    'db_column' => $alias.$filter,
                    'db_operation' => '=',
                    'db_value' => $val];
            }
        }
    }
}

$options = [];
switch (getParam('method')) {
    case "datatable-get":
        if($loggedin && $base->unam_validVar([$datatables['tables'][getParam('tableid')]]) && $datatables['tables'][getParam('tableid')]['enabled']){
            $table = &$datatables['tables'][getParam('tableid')];
            $options = $table;
            $options['columns'] = array_values($options['columns']);
            for($i = 0, $ic = count($options['columns']); $i<$ic; $i++){
                $options['columns'][$i]['db_column'] = db_getPrefix($table['db_column_prefix'], $options['columns'][$i]['db_column_prefix']).$options['columns'][$i]['db_column'];
            }
            if($base->unam_validVar([$table['db_use_globalwhere']])){
                if($base->unam_validVar([$datatables['db_globalwhere']])){
                    foreach($datatables['db_globalwhere'] as $where){
                        $options['db_where'][] = ['db_column'=>($table['db_globalwhere_prefix'] ?? '').$where['db_column'], 'db_operation'=>$where['db_operation'], 'db_value'=>$where['db_value']];
                    }
                }
            }
            db_setFilters($options, ($table['db_filters_prefix'] ?? ''), [($table['db_allowed_filters'] ?? '')]);

            if(getParam('tableid') === 'miner-table' && isset($_SESSION['hide_offline_miners']) && $_SESSION['hide_offline_miners'] == true) {
                $options['db_where'][] = [
                    'db_column' => 'ms_lastConnection',
                    'db_operation' => '>',
                    'db_value' => date('Y-m-d H:i:s', strtotime('-3 minutes'))];
            }
        }
        break;
}

if($options) {
    echo json_encode(
        SSP::process(getConn(), $_POST, $options)
    );
}else{
    echo json_encode([]);
}