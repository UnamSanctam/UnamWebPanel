<?php
require_once 'security.php';
$dir = dirname(__FILE__).'/internalPages/'.getParam('page').'Ajax.php';
if(file_exists($dir)){
    include $dir;
}else{
    include 'internalPages/minersAjax.php';
}