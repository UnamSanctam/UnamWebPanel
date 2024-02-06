<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/assets/php/security.php';

$configID = getParam('id') ?: 1;

$currentconfig = [];
$configoptions = "";
$configs = $base->unam_dbSelect(getConn(), 'configs', 'cf_configID, cf_name, cf_data', [], false, true);
foreach ($configs as $configdata) {
    $configoptions .= "<option value='{$configdata['cf_configID']}' ".($configdata['cf_configID'] == $configID ? 'selected' : '').">{$configdata['cf_name']}</option>";
    if($configdata['cf_configID'] == $configID){
        $currentconfig = $configdata;
    }
}
?><!DOCTYPE html>
<html lang="<?= $langID ?>">
<head>
    <?php include dirname(__DIR__).'/assets/php/styles.php'; ?>
    <title>Unam Web Panel &mdash; <?= $larr['configurations'] ?></title>
</head>
<body class="dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
    <?php include dirname(__DIR__).'/assets/php/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2"></div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1><?= $larr['configurations'] ?></h1>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-lg-6 col-xl-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= $larr['add'].' '.$larr['configuration'] ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="#" class="form-submit-reload">
                                                <input type="hidden" name="action" value="config-add">
                                                <div class="form-group">
                                                    <label class="control-label"><?= $larr['name'] ?></label>
                                                    <input type="text" class="form-control" name="name" value="" placeholder="" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label><?= $larr['configuration'] ?> JSON</label>
                                                    <textarea type="text" class="form-control" name="data"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary btn-block">
                                                        <?= $larr['add'] ?>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= $larr['edit'].' '.$larr['configuration'] ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label><?= $larr['choose'].' '.$larr['configuration'] ?></label>
                                                <select  class="form-control select2 nav-select">
                                                    <?= $configoptions ?>
                                                </select>
                                            </div>
                                            <form action="#" class="form-submit">
                                                <input type="hidden" name="action" value="config-update">
                                                <input type="hidden" name="index" value="<?= $configID ?>">
                                                <div class="form-group">
                                                    <label><?= $larr['configuration'] ?> JSON</label>
                                                    <textarea type="text" class="form-control" name="data"><?= $base->unam_sanitize($currentconfig['cf_data'] ?? '{}') ?></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col">
                                                        <button type="submit" class="btn btn-primary btn-block">
                                                            <?= $larr['save'] ?>
                                                        </button>
                                                    </div>
                                                    <div class="form-group col">
                                                        <a href="#" class="btn <?= $configID == 1 || $configID == 2 ? 'disabled ' : '' ?> col btn-danger ajax-action-reload ajax-action-confirm" data-action="config-remove" data-index="<?= $configID ?>"><?= $larr['remove'] ?></a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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