<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */

class unam_lib {
    private static $debugStart;
    private static $usingCustomErrorHandler;

    function __construct() {
        self::$debugStart = microtime(true);
        self::$usingCustomErrorHandler = false;
    }

    function unam_dbSelect($conn, $table_name, $fields, $where_array = null, $rowcount=false, $fetchAll=false, $extras=''){
        $where_flag = [];
        $where_flag_values = [];
        if(isset($where_array) && is_array($where_array)){
            foreach($where_array as $key=>$value) {
                if($key == '$CUSTOM'){
                    $where_flag[] = "$value";
                }else if(is_array($value)){
                    $where_flag[] = "$key $value[0] ?";
                    $where_flag_values[] = $value[1];
                }else{
                    $where_flag[] = "$key = ?";
                    $where_flag_values[] = $value;
                }
            }
            $where_flag_string = implode(' AND ', $where_flag);
        }
        try{
            $s = $conn->prepare("SELECT ".($rowcount ? "COUNT(*) as unam_totalCount" : $fields)." FROM $table_name".($where_array && count($where_array) > 0 ? " WHERE $where_flag_string" : "")." $extras");
            $s->execute($where_flag_values);
            if($rowcount){
                return $s->fetch(PDO::FETCH_ASSOC)['unam_totalCount'];
            }
            if($fetchAll) {
                return $s->fetchAll(PDO::FETCH_ASSOC);
            }
            return $s->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            self::unam_writeError("</br><b>An error occured: </b> </br>{$e->getMessage()}");
            return [];
        }
    }

    function unam_dbInsert($conn, $table, $arr)
    {
        try {
            $names = join(',', array_keys($arr));
            $params = [];
            $valcount = (is_array(reset($arr)) ? count(reset($arr))  : 1);
            $keycount = count(array_keys($arr));
            if(is_array(reset($arr))){
                for($i = 0; $i < $valcount; $i++){
                    $params = array_merge($params, array_column($arr, $i));
                }
            }else{
                $params = array_values($arr);
            }
            $values =  substr(str_repeat(',('.substr(str_repeat(',?', $keycount), 1).')', $valcount), 1);
            $s = $conn->prepare("INSERT INTO $table ($names) VALUES $values");
            return $s->execute($params);
        }
        catch(PDOException $e){
            self::unam_writeError("</br><b>An error occured: </b> </br>{$e->getMessage()}");
            return [];
        }
    }

    function unam_dbUpdate($conn, $tableName, $colsArray, $where_array)
    {
        $UpdateString = [];
        $ExecuteString = [];
        foreach($colsArray as $key=>$value)
        {
            $UpdateString[] = "$key = ?";
            $ExecuteString[] = $value;
        }

        $where_flag = [];
        foreach($where_array as $key=>$value)
        {
            if($key == '$CUSTOM'){
                $where_flag[] = "$value";
            }else if(is_array($value)){
                $where_flag[] = "$key $value[0] ?";
                $ExecuteString[] = $value[1];
            }else{
                $where_flag[] = "$key = ?";
                $ExecuteString[] = $value;
            }
        }

        try {
            $s = $conn->prepare("UPDATE $tableName SET ".implode(', ', $UpdateString)." WHERE ".implode(' AND ', $where_flag));
            return $s->execute($ExecuteString);
        }
        catch(PDOException $e){
            self::unam_writeError("</br><b>An error occured: </b> </br>{$e->getMessage()}");
            return [];
        }
    }

    function unam_dbDelete($conn, $table_name, $where_array)
    {
        $where_flag = [];
        $where_flag_values = [];
        foreach($where_array as $key=>$value)
        {
            if($key == '$CUSTOM'){
                $where_flag[] = "$value";
            }else if(is_array($value)){
                $where_flag[] = "$key $value[0] ?";
                $where_flag_values[] = $value[1];
            }else{
                $where_flag[] = "$key = ?";
                $where_flag_values[] = $value;
            }
        }

        $where_flag_string = implode(' AND ', $where_flag);
        try {
            $s = $conn->prepare("DELETE FROM $table_name WHERE $where_flag_string");
            return $s->execute($where_flag_values);
        }
        catch(PDOException $e) {
            self::unam_writeError("</br><b>An error occured: </b> </br>{$e->getMessage()}");
            return [];
        }
    }

    function unam_filterParameter($param, $maxlength = 1000, $default = ''){
        if(!isset($_POST[$param]) && !isset($_GET[$param])) {
            return $default;
        }
        $fparam = self::unam_arrayWalkRecursive($_POST[$param] ?? $_GET[$param], function(&$v){ global $maxlength; $v = strip_tags(substr($v, $maxlength)); });
        return (count($fparam) == 1 ? $fparam[0] : $fparam);
    }

    function unam_filterAllParameters($maxlength = 1000, $default = '')
    {
        $paramarr = array_merge($_POST, $_GET);
        $outarr = [];
        if(is_array($paramarr)){
            foreach($paramarr as $param=>$data){
                $outarr[$param] = self::unam_filterParameter($param, $maxlength, $default);
            }
        }
        return $outarr;
    }

    function unam_arrayWalkRecursive($arr, $function){
        $arr = is_array($arr) ? $arr : [$arr];
        array_walk_recursive($arr, $function);
        return $arr;
    }

    function unam_validVar($var){
        $var = (is_array($var) ? $var : [$var]);
        for($ivar = 0; $ivar < count($var); $ivar++){
            if(!isset($var[$ivar]) || empty($var[$ivar])){
                return false;
            }
        }
        return true;
    }

    function unam_recursiveGenerator($conn, $tableName, $fieldName, $length=32, $numbersonly=false)
    {
        $id = self::unam_generateRandomString($length, $numbersonly);
        if(self::unam_dbSelect($conn, $tableName, $fieldName, [$fieldName=>$id])){
            self::unam_recursiveGenerator($conn, $tableName, $fieldName, $length);
        }
        else{
            return $id;
        }
        return false;
    }

    function unam_generateRandomString($length=32, $numbersonly=false) {
        return substr(str_shuffle(str_repeat($x='0123456789'.($numbersonly == false ? 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' : ''), ceil($length/strlen($x)) )),1,$length);
    }

    function unam_multiStrpos($string, $check, $getResults = false)
    {
        $result = array();
        $check = (array) $check;

        foreach ($check as $s)
        {
            $pos = strpos($string, $s);

            if ($pos !== false)
            {
                if ($getResults)
                {
                    $result[$s] = $pos;
                }
                else
                {
                    return $pos;
                }
            }
        }

        return empty($result) ? false : $result;
    }

    function unam_checkCondition($cond, $resp){
        if($cond === true){
            self::unam_echoFailure($resp);
            die();
        }
    }

    function unam_echoSuccess($successmsg){
        echo json_encode(['response' => 'success', 'successmsg'=>$successmsg]);
    }
    function unam_echoFailure($errormsg){
        echo json_encode(['response'=>'failure', 'errormsg'=>$errormsg]);
    }


    function unam_getBrowserLanguages($available = [], $default = 'en') {
        if (isset( $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ])) {
            $langs = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
            if(empty($available)) {
                return $langs[0];
            }
            foreach($langs as $lang){
                $lang = substr( $lang, 0, 2 );
                if(in_array( $lang, $available)){
                    return $lang;
                }
            }
        }
        return $default;
    }

    function unam_toggleCustomErrorHandling(){
        if(self::$usingCustomErrorHandler){
            restore_error_handler();
            self::$usingCustomErrorHandler = false;
            return false;
        }else{
            set_error_handler([$this, 'unam_customErrorHandler']);
            self::$usingCustomErrorHandler = true;
            return true;
        }
    }

    function unam_customErrorHandler($errno, $errstr, $error_file, $error_line, $error_context = null)
    {
        global $SYSTEM_PHP_ERROR;
        $SYSTEM_PHP_ERROR=false;
        $err_hostname_ip = $_SERVER['REMOTE_ADDR'] ;
        $errout="";

        $errout .= "<br/><b>Date and Time:</b> ".date('Y/m/d H:i:s');

        $errout .= "<br/><b>In file:</b> $error_file";
        $errout .= "<br/><b>On line:</b> $error_line";
        $errout .= "<br/><b>Error was: </b> [$errno] $errstr";
        $errout .= "<br/><details><summary><b>Error context:</b></summary><p>".(is_array($error_context) ? json_encode($error_context) : $error_context)."</p></details>";
        $errout .= "<br/><b>Remote IP:</b> $err_hostname_ip";
        $errout .= "<br/><b>Session Data:</b> ".json_encode($_SESSION);
        $errout .= "<br/>Ending Script";
        $errout .= "<hr />";

        self::unam_writeError($errout);
        $SYSTEM_PHP_ERROR=true;
    }

    function unam_writeError($errormessage, $debug_trace=true){
        file_put_contents(__DIR__."/Logs/php-error-".date('m-d-Y').".html", "ERROR: $errormessage ".($debug_trace ? "<details><summary><b>Debug Trace: </b></summary><p>".json_encode(array_slice(debug_backtrace(), 1)) .'</p></details></br>': ''), FILE_APPEND);
    }

    function unam_debugOutput($id){
        file_put_contents(__DIR__."/Logs/php-exec-time-".date('m-d-Y').".html", "{$id}: ".(microtime(true) - self::$debugStart)." SEC </br>", FILE_APPEND);
    }
}