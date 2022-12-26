<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/config.php';
$conn = null;

function getConn(){
    global $conn, $config;
    if(!empty($conn)){
        return $conn;
    }else{
        $connectionString = "sqlite:{$config['db_file']}";
        try
        {
            $conn = new PDO($connectionString, NULL, NULL, [
                PDO::ATTR_PERSISTENT=>true,
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES=>false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $conn->exec('PRAGMA journal_mode = WAL;PRAGMA synchronous = NORMAL;PRAGMA temp_store = MEMORY;PRAGMA foreign_keys = ON;');
            return $conn;
        }
        catch(PDOException $e)
        {
            file_put_contents("db-error-".date('m-d-Y').".html", "ERROR: {$e->getMessage()}<br />", FILE_APPEND);
        }
    }
    return false;
}

function closeConn(){
    global $conn;
    $conn = null;
}