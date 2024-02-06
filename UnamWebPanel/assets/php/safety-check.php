<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__, 2).'/config.php';

if (!class_exists('PDO')) {
    echo '<p style="color:red">The required class PDO could not be found.</p>';
    die();
}

if (!in_array('sqlite', PDO::getAvailableDrivers())) {
    echo '<p style="color:red">The required SQLite PDO driver is not enabled.</p>';
    die();
}

if (!file_exists($config['db_file'])) {
    echo "<p style='color:red'>The SQLite database file specified in the configuration could not be found.</p>";
    die();
}

$db_folder = dirname($config['db_file']);
if (!is_readable($db_folder)) {
    echo "<p style='color:red'>The folder containing the SQLite database ({$db_folder}) is not readable, please give the folder appropriate read permissions.</p>";
    die();
}

if (!is_writable($db_folder)) {
    echo "<p style='color:red'>The folder containing the SQLite database ({$db_folder}) is not writable, please give the folder appropriate write permissions.</p>";
    die();
}

if (!is_readable($config['db_file'])) {
    echo "<p style='color:red'>The SQLite database file ({$config['db_file']}) is not writable, please give the file appropriate write permissions.</p>";
    die();
}

if (!is_writable($config['db_file'])) {
    echo "<p style='color:red'>The SQLite database file ({$config['db_file']}) is not writable, please give the file appropriate write permissions.</p>";
    die();
}