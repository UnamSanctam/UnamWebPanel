<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/assets/php/security.php';
require_once dirname(__DIR__).'/assets/php/datatables.php';
?><!DOCTYPE html>
<html lang="<?= $langID ?>">
<head>
    <?php include dirname(__DIR__).'/assets/php/styles.php'; ?>
    <title>Unam Web Panel &mdash; <?= $larr['miners'] ?></title>
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
                            <h1><?= $larr['miners'] ?></h1>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= "<h4>{$larr['miners']}</h4>" ?></h4>
                                            <div class="card-tools">
                                                <?= $larr['hide_offline_miners'] ?>
                                                <label class="round-switch">
                                                    <input type="checkbox" class="hide-offline-miners" <?= $_SESSION['hide_offline_miners'] ?? false ? 'checked' : '' ?>>
                                                    <span class="round-slider"></span>
                                                </label>
                                                <?= $larr['auto_refresh'] ?>
                                                <label class="round-switch">
                                                    <input type="checkbox" class="refresh-datatables">
                                                    <span class="round-slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <?= generateDatatable('miner-table') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= $larr['remove_offline_miners'] ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="#" class="form-submit">
                                                <input type="hidden" name="action" value="miner-clean">
                                                <div class="form-group">
                                                    <label class="control-label"><?= $larr['minimum_days_offline'] ?>:</label>
                                                    <input type="number" class="form-control" name="amount" value="1" placeholder="" min="1" required="">
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary btn-block col btn-danger">
                                                        <?= $larr['remove'] ?>
                                                    </button>
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