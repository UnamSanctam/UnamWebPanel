<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/assets/php/security.php';
require_once dirname(__DIR__).'/assets/php/datatables.php';
?><!DOCTYPE html>
<html lang="<?= $langID ?>">
<head>
    <?php include dirname(__DIR__).'/assets/php/styles.php'; ?>
    <title>Unam Web Panel &mdash; <?= $larr['ip_blocking'] ?></title>
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
                            <h1><?= $larr['ip_blocking'] ?></h1>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= "<h4>{$larr['ip_blocking']}</h4>" ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?= generateDatatable('ipblocking-table') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= $larr['add_ip_block'] ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="#" class="form-submit form-submit-reset">
                                                <input type="hidden" name="action" value="ipblock-add">
                                                <div class="form-group">
                                                    <label class="control-label">IP</label>
                                                    <input type="text" class="form-control" name="ip" value="" placeholder="" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label><?= $larr['note'] ?></label>
                                                    <textarea type="text" class="form-control" name="note"></textarea>
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