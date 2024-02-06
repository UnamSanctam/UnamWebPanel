<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/assets/php/session-header.php';

if($loggedin){
    header("Location: miners.php");
}
?><!DOCTYPE html>
<html lang="<?= $langID ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Unam Web Panel &mdash; <?= $larr['login'] ?></title>

    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <link rel="stylesheet" href="../assets/modules/fontawesome-free/css/fontawesome.min.css">
    <link rel="stylesheet" href="../assets/modules/fontawesome-free/css/solid.min.css">
    <link rel="stylesheet" href="../assets/modules/izitoast/iziToast.min.css">
    <link rel="stylesheet" href="../assets/modules/select2/select2.min.css">
    <link rel="stylesheet" href="../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body class="external-page">
<div class="external-page-box">
    <div class="card card-dark card-outline card-primary">
        <div class="card-header text-center">
            <h4><b>Unam</b> Web Panel</h4>
        </div>
        <div class="card-body">
            <form action="#" class="unam-login">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="action" value="Login">
                <div class="form-group">
                    <label class="control-label"><?= $larr['password'] ?></label>
                    <div class="input-group-append">
                        <input type="password" class="form-control" name="password" placeholder="<?= $larr['password'] ?>" required="">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-block btn-primary">
                    <?= $larr['login'] ?>
                </button>
            </form>
        </div>
    </div>
    <div class="mt-3">
        <select class="form-control select2 nav-lang" data-minimum-results-for-search="-1">
            <?php
            foreach($config['languages'] as $key => $value) {
                echo "<option ".($langID == $key ? 'selected' : '')." value='{$key}'>{$value}</option>";
            }
            ?>
        </select>
    </div>
</div>

<script src="../assets/modules/jquery/jquery-3.7.1.min.js"></script>
<script src="../assets/modules/izitoast/iziToast.min.js"></script>
<script src="../assets/modules/select2/select2.min.js"></script>
<script src="../__UNAM_LIB/unam_lib.js"></script>
<script type="text/javascript" nonce="<?= $csp_nonce ?>">
    $('.unam-login').on('submit', function(e){
        e.preventDefault();
        unam_jsonAjax('POST', '../api/ajax-auth.php', $(this).serialize(), function(){
            window.location.href = 'miners.php';
        }, function(error){ iziToast.error({ title: 'Error', message: error, position: 'topRight' }); });
    });

    $(".select2").select2();
    $('.nav-lang').on('select2:select', function(e){
        unam_jsonAjax('POST', '../api/ajax-sitewide.php', { action: 'lang-change', newlangID: e.params.data.id }, function(){
            location.reload();
        }, function(){ });
    });
</script>
</body>
</html>