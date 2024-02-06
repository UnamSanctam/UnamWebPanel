<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once 'safety-check.php';
require_once dirname(__DIR__, 2).'/class/class.base.php';
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

$base = new base();

if($config['errorlog_enable']) {
    $base->unam_toggleCustomErrorHandling();
}

if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$loggedin = !empty($_SESSION['logged_in']) && password_verify($config['password'], $_SESSION['logged_in']);

$currentDate = date('Y-m-d H:i:s');

if(empty($_SESSION['lang'])){
    $_SESSION['lang'] = $base->unam_getBrowserLanguages(array_keys($config['languages']));
}

$langID = !empty($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

require_once dirname(__DIR__, 2).'/lang/lang.php';

$larr = [];
foreach($langarr as $key=>$val){
    $larr[$key] = $val[$langID] ?? $val['en'];
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