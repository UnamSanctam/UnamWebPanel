<?php
require_once dirname(__DIR__, 2).'/config.php';

if (!class_exists('PDO')) {
    echo "<p style='color:red'>The required class PDO could not be found.</p>";
    die();
}

if (!in_array('sqlite', PDO::getAvailableDrivers())) {
    echo "<p style='color:red'>The required SQLite PDO driver IS NOT enabled.</p>";
    die();
}

if (!file_exists($config['db_file'])) {
    echo "<p style='color:red'>The SQLite database file specified in the configuration could not be found.</p>";
    die();
}