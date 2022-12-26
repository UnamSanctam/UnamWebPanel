<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__, 1).'/security.php';
require_once dirname(__DIR__, 1).'/assets/php/templates.php';

$charts = '';
if($config['hashrate_history_enable']) {
    $hashratecon = getConn()->query("SELECT hr_algorithm, SUM(hr_hashrate) AS hashrate, hr_date FROM hashrate GROUP BY hr_date, hr_algorithm ORDER BY hr_date");
    $hashrate = $hashratecon->fetchAll(PDO::FETCH_ASSOC);

    if ($hashrate) {
        $hashratearr = [];

        foreach ($hashrate as $value) {
            $hashratearr[$value['hr_algorithm']][] = ['x' =>date('Y-m-d H:i:s', $value['hr_date']), 'y' => $value['hashrate']];
        }

        foreach ($hashratearr as $key => $value) {
            $charts .= unamtCard('col-md-3', "<h4>Total Hashrate: {$key}</h4>", '',
                templateHashrateChart($key, $value)
            );
        }
    }
}

$statcon = getConn()->query("SELECT COUNT(CASE WHEN ms_status = 2 THEN 1 ELSE NULL END) AS active_amount, 
       COUNT(CASE WHEN ms_status = 4 THEN 1 ELSE NULL END) AS paused_amount, 
       (SELECT COUNT(1) FROM miners WHERE ms_lastConnection < datetime('now', '-3 minute')) AS offline_amount, 
       (SELECT COUNT(1) FROM miners) AS total_amount 
       FROM miners WHERE ms_lastConnection > datetime('now', '-3 minute')");
$stats = $statcon->fetch(PDO::FETCH_ASSOC);

echo unamtSection($larr['Miners'],
    unamtRow(
        unamtCard('col-xl-1 col-lg-2 col-md-3 col-sm-6 col-6', "<h4>{$larr['Total']}</h4>", '',
            $stats['total_amount']
        ).
        unamtCard('col-xl-1 col-lg-2 col-md-3 col-sm-6 col-6', "<h4>{$larr['Active']}</h4>", '',
            $stats['active_amount']
        , ['cardclasses'=>'bg-success']).
        unamtCard('col-xl-1 col-lg-2 col-md-3 col-sm-6 col-6', "<h4>{$larr['Paused']}</h4>", '',
            $stats['paused_amount']
        , ['cardclasses'=>'bg-warning']).
        unamtCard('col-xl-1 col-lg-2 col-md-3 col-sm-6 col-6', "<h4>{$larr['Offline']}</h4>", '',
            $stats['offline_amount']
        , ['cardclasses'=>'bg-danger'])
    , ['classes'=>'statistic-cards']).
    unamtRow(
        $charts
    ).
    unamtRow(
        templateDatatableX('miner-table')
    ).
    unamtRow(
        unamtCard('col-md-4', "<h4>{$larr['remove_offline_miners']}</h4>", '',
            unamtFormContainer('miner-clean', 'api/ajax-actions.php',
                unamtFormGroup(unamtInput("{$larr['minimum_days_offline']}:", 'amount', ['type'=>'number', 'value'=>'1', 'extras'=>'min="1" required'])).
                unamtFormGroup(unamtSubmit($larr['Remove'], ['classes'=>'col btn-danger']))
            )
        )
    )
);
