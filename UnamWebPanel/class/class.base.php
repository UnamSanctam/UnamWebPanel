<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
error_reporting(E_ALL);
ini_set('display_errors', 'off');
ini_set("log_errors", 1);
ini_set("error_log", "error.log");
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once dirname(__DIR__) . '/__UNAM_LIB/unam_lib.php';
require_once dirname(__DIR__) . '/config.php';
require_once 'db.php';

class base extends unam_lib
{
    function unsetSession()
    {
        unset($_SESSION['HTTP_USER_AGENT']);
        unset($_SESSION['logged_in']);
    }

    function tableModify($conn, $type, $table, $alias, $shortfields, $wherefields, $index=null){
        $inputArr = [];
        if(!empty($shortfields)) {
            foreach ($shortfields as $field) {
                $inputfield = (!is_array($field) ? $field : $field[0]);
                $inputval = (!is_array($field) ? getParam($field) : $field[1]);
                if(isset($inputval)) {
                    $inputArr["{$alias}_{$inputfield}"] = $inputval;
                }
            }
        }
        $whereArr = [];
        if(!empty($wherefields)){
            foreach($wherefields as $field){
                $inputfield = (!is_array($field) ? $field : $field[0]);
                $inputval = (!is_array($field) ? ($index ?? getParam('index')) : $field[1]);
                $whereArr["{$alias}_{$inputfield}"] = $inputval;
            }
        }
        switch($type){
            case 'insert':
                self::unam_dbInsert($conn, $table, $inputArr);
                break;
            case 'update':
                if(!empty($whereArr) && !empty($inputArr)) {
                    self::unam_dbUpdate($conn, $table, $inputArr, $whereArr);
                }
                break;
            case 'delete':
                if(!empty($whereArr)) {
                    self::unam_dbDelete($conn, $table, $whereArr);
                }
                break;
        }
    }
}