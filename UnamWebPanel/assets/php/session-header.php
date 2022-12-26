<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__, 2).'/class/class.base.php';
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

$base = new base();

$base->unam_toggleCustomErrorHandling();

$hostaddress = $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';
if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if(!isset($_SESSION['lang']) || empty($_SESSION['lang'])){
    $_SESSION['lang'] = $base->unam_getBrowserLanguages(array_keys($config['languages']));
}

$loggedin = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;

$currentDate = date('Y-m-d H:i:s');

$langID = !empty($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

require_once dirname(__DIR__, 2).'/lang/lang.php';

$larr = [];
foreach($langarr as $key=>$val){
    $larr[$key] = $val[$langID] ?? $val['en'];
}

function checkRedir($cond, $page){
    if($cond){
        echo json_encode(['redir'=>$page]);
        die();
    }
}

$paramsarr = $base->unam_filterAllParameters(10000);
function getParam($param, $default=''){
    global $paramsarr;
    return ($paramsarr[$param] ?? $default);
}

$cf = function($function) {
    return $function;
};

$minerconfigs = null;

function getMinerConfigurations(){
    global $base, $minerconfigs;
    if(empty($minerconfigs)){
        $minerconfigs = $base->unam_dbSelect(getConn(), 'configs', 'cf_configID, cf_name, cf_data', [], 0, 1);
    }
    return $minerconfigs;
}

function checkJSON($input) {
    json_decode($input, true);
    if(json_last_error() != JSON_ERROR_NONE) {
        return false;
    }
    return true;
}