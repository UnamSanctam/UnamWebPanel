<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once 'security.php';

function active_link($target) {
    return basename($_SERVER["SCRIPT_NAME"]) === $target ? 'active' : '';
}
?><nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="logout.php" class="nav-link"><p><?php echo $larr['logout']; ?></p></a>
        </li>
    </ul>
</nav>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="miners.php" class="brand-link">
        <img src="../assets/img/favicon.png" alt="Unam Sanctam" class="brand-image">
        <span class="brand-text font-weight-light">Unam Web Panel</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 pt-3 mb-3 d-flex">
            <select class="form-control select2 nav-lang" data-minimum-results-for-search="-1">
                <?php
                foreach($config['languages'] as $key => $value) {
                    echo "<option ".($langID == $key ? 'selected' : '')." value='{$key}'>{$value}</option>";
                }
                ?>
            </select>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="miners.php" class="nav-link <?= active_link('miners.php') ?>"><i class="nav-icon fas fa-network-wired"></i> <p><?php echo $larr['miners']; ?></p></a>
                </li>
                <li class="nav-item">
                    <a href="statistics.php" class="nav-link <?= active_link('statistics.php') ?>"><i class="nav-icon fas fa-chart-pie"></i> <p><?php echo $larr['statistics']; ?></p></a>
                </li>
                <li class="nav-item">
                    <a href="configurations.php" class="nav-link <?= active_link('configurations.php') ?>"><i class="nav-icon fas fa-cogs"></i> <p><?php echo $larr['configurations']; ?></p></a>
                </li>
                <li class="nav-item">
                    <a href="ip-blocking.php" class="nav-link <?= active_link('ip-blocking.php') ?>"><i class="nav-icon fas fa-ban"></i> <p><?php echo $larr['ip_blocking']; ?></p></a>
                </li>
            </ul>
        </nav>
    </div>
</aside>