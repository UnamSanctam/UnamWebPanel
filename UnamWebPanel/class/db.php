<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__).'/assets/php/session-header.php';
$conn = null;

function getConn($type='conn'){
    global $base, $conn, $config;
    if($type == 'conn'){
        if(!empty($conn)){
            return $conn;
        }else{
            $connectionString = "sqlite:{$config['db_file']}";
            try
            {
                $conn = new PDO($connectionString);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->exec('PRAGMA synchronous = NORMAL;PRAGMA temp_store = MEMORY;');
                return $conn;
            }
            catch(PDOException $e)
            {
                $base->unam_writeError("</br><b>An error occured: </b> </br>{$e->getMessage()}");
            }
        }
    }
    return false;
}

function closeConn(){
    global $conn;
    $conn =  null;
}