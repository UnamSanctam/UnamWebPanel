<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__, 2).'/config.php';
?><footer class="main-footer">
    <strong>Copyright &copy; 2021-<?php echo date("Y"); ?> <a href="https://github.com/UnamSanctam">Unam Sanctam</a>.</strong>
    For educational purposes.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> <?php echo $config["unam_version"] ?>
    </div>
</footer>