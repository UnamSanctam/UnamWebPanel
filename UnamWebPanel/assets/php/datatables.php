<?php
    /* Made by Unam Sanctam https://github.com/UnamSanctam */
    require_once 'templates.php';

    $datatables = [
        'tables'=>[
            'miner-table'=>[
                'db_alias'=>'ms',
                'db_table'=>'miners',
                'db_primary_key'=>'ms_minerID',
                'db_column_prefix'=>'ms_',
                'display'=>$larr['Miners'],
                'html_header'=>"<h4>{$larr['Miners']}</h4>",
                'enabled'=>true,
                'columns'=>[
                    'minerID'=>[
                        'db_column'=>'minerID',
                        'display'=>$larr['Miner'].' ID'
                    ],
                    'uqhash'=>[
                        'db_column'=>'uqhash',
                        'display'=>$larr['unique_id']
                    ],
                    'ip'=>[
                        'db_column'=>'ip',
                        'display'=>'IP'
                    ],
                    'status'=>[
                        'db_column'=>'status',
                        'display'=>$larr['Status'],
                        'formatting'=>function($d, $s){
                            return unamtMinerStatus(isset($s['ms_lastConnection']) && ((strtotime(date("Y-m-d H:i:s")) - strtotime($s['ms_lastConnection'])) > 300) ? 6 : $d);
                        }
                    ],
                    'algorithm'=>[
                        'db_column'=>'algorithm',
                        'display'=>$larr['Algorithm']
                    ],
                    'hashrate'=>[
                        'db_column'=>'hashrate',
                        'display'=>$larr['Hashrate'],
                        'formatting'=>function($d){
                            return unamtFormatHashrate($d);
                        }
                    ],
                    'pool'=>[
                        'db_column'=>'pool',
                        'display'=>$larr['Pool']
                    ],
                    'port'=>[
                        'db_column'=>'port',
                        'display'=>$larr['Port']
                    ],
                    'user'=>[
                        'db_column'=>'user',
                        'display'=>$larr['User']
                    ],
                    'worker'=>[
                        'db_column'=>'worker',
                        'display'=>$larr['Worker']
                    ],
                    'password'=>[
                        'db_column'=>'password',
                        'display'=>$larr['Password']
                    ],
                    'username'=>[
                        'db_column'=>'username',
                        'display'=>$larr['Username']
                    ],
                    'computername'=>[
                        'db_column'=>'computername',
                        'display'=>$larr['computer_name']
                    ],
                    'type'=>[
                        'db_column'=>'type',
                        'display'=>$larr['Type']
                    ],
                    'gpu'=>[
                        'db_column'=>'gpu',
                        'display'=>'GPU'
                    ],
                    'cpu'=>[
                        'db_column'=>'cpu',
                        'display'=>'CPU'
                    ],
                    'remoteURL'=>[
                        'db_column'=>'remoteURL',
                        'display'=>"{$larr['Remote']} {$larr['URL']}"
                    ],
                    'lastConnection'=>[
                        'db_column'=>'lastConnection',
                        'display'=>$larr['last_connection'],
                    ],
                    'config'=>[
                        'db_column'=>'config',
                        'display'=>$larr['Configuration'],
                        'formatting'=>function($d, $s){
                            global $larr;
                            $configs = getMinerConfigurations();
                            $configoptions = '';
                            if($configs) {
                                $configoptions .= "<option value='0'>{$larr['None']}</option>";
                                foreach ($configs as $configdata) {
                                    $configoptions .= "<option value='{$configdata['cf_configID']}' " . ($configdata['cf_configID'] == $d ? 'selected' : '') . ">{$configdata['cf_name']}</option>";
                                }
                            }
                            return unamtSelect('','config', $configoptions, ['classes'=>'select-miner-config', 'extras'=>"data-method='miner-config' data-index='{$s[0]}'"]);
                        }
                    ],
                    'actions'=>[
                        'db_column'=>'minerID',
                        'display'=>$larr['Actions'],
                        'formatting'=>function($d, $s){
                            global $larr;
                            return unamtAjaxButton("{$larr['Remove']} {$larr['Miner']}",'miner-remove', $d, ['classes'=>'btn-danger']);
                        }
                    ]
                ],
                'db_filters_prefix'=>'ms_',
                'db_allowed_filters'=>[],
                'db_globalwhere_prefix'=>'ms_',
                'db_use_globalwhere'=>false
            ]
        ]
    ];