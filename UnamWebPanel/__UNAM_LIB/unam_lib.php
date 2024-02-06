<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */

class unam_lib {
    private static $usingCustomErrorHandler;

    function __construct() {
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
            return false;
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

    function unam_sanitize($input) {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = self::unam_sanitize($value);
            }
            return $input;
        }
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8', false);
    }


    function unam_filterParameter($param, $maxlength = 1000, $default = ''){
        if(!isset($_POST[$param]) && !isset($_GET[$param])) {
            return $default;
        }
        $fparam = self::unam_arrayWalkRecursive($_POST[$param] ?? $_GET[$param], function(&$v) use(&$maxlength){ $v = self::unam_sanitize(substr($v, 0, $maxlength)); });
        return (count($fparam) == 1 ? $fparam[0] : $fparam);
    }

    function unam_filterAllParameters($maxlength = 1000, $default = '') {
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

    function unam_checkCondition($cond, $resp){
        if($cond === true){
            self::unam_echoFailure($resp);
            die();
        }
    }

    function unam_echoSuccess($successmsg=''){
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
                $lang = substr($lang, 0, 2);
                if(in_array( $lang, $available)){
                    return $lang;
                }
            }
        }
        return $default;
    }

    function unam_toggleCustomErrorHandling() {
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

    function unam_customErrorHandler($errno, $errstr, $error_file, $error_line, $error_context = null) {
        global $SYSTEM_PHP_ERROR;
        $SYSTEM_PHP_ERROR=false;
        $err_hostname_ip = $_SERVER['REMOTE_ADDR'] ;

        $errout = "<br/><b>Date and Time:</b> ".date('Y/m/d H:i:s');
        $errout .= "<br/><b>In file:</b> $error_file";
        $errout .= "<br/><b>On line:</b> $error_line";
        $errout .= "<br/><b>Error was: </b> [$errno] $errstr";
        $errout .= "<br/><b>Remote IP:</b> $err_hostname_ip";
        $errout .= "<hr/>";

        self::unam_writeError($errout);
        $SYSTEM_PHP_ERROR=true;
    }

    function unam_writeError($errormessage){
        if(self::$usingCustomErrorHandler) {
            file_put_contents(__DIR__."/Logs/php-error-".date('d-m-Y').".html", "ERROR: $errormessage", FILE_APPEND);
        }
    }
}