<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/config.php';
$conn = null;

function getConn() {
    global $conn, $config;
    if($conn) {
        return $conn;
    } else {
        $connectionString = "sqlite:{$config['db_file']}";
        try {
            $conn = new PDO($connectionString, NULL, NULL, [
                PDO::ATTR_TIMEOUT=>60,
                PDO::ATTR_PERSISTENT=>false,
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES=>false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $conn->exec('PRAGMA journal_mode = WAL;PRAGMA synchronous = OFF;PRAGMA temp_store = MEMORY;PRAGMA foreign_keys = OFF;PRAGMA mmap_size = 268435456;PRAGMA cache_size = -32000;PRAGMA journal_size_limit = 10000000;');
            return $conn;
        } catch(PDOException $e) {
            if($config['errorlog_enable']) {
                file_put_contents(dirname(__DIR__)."/__UNAM_LIB/Logs/db-error-".date('d-m-Y').".log", "ERROR: {$e->getMessage()}\r\n", FILE_APPEND);
            }
        }
    }
    return false;
}

function closeConn() {
    global $conn;
    $conn = null;
}