<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
$config = [];

$config['password'] = 'UnamSanctam';

$config['db_file'] = "protected_path_to_unamwebpanel.db";
/*
 * Example:
 * $config['db_file'] = dirname(__FILE__, 1)."/unamwebpanel.db";
 * Will use the unamwebpanel.db one folder back from where
 * config.php is located, DB should be placed outside public_html
 */

$config['url_pages'] = 'index.php';
$config['url_login'] = 'login.php';
$config['url_pageloader'] = 'page-loader.php';
$config['url_authajax'] = 'auth-ajax.php';
$config['url_customtable'] = "api/custom-table.php";
$config['url_ajaxactions'] = "api/ajax-actions.php";
$config['url_ajaxsitewide'] = "api/ajax-sitewide.php";

$config['failedlogin_blocktime'] = 15;
$config['failedlogin_blocktries'] = 5;

$config['api_minreportime'] = 50;

$config['languages'] = ['en', 'sv'];

$config['unam_version'] = "1.0";

global $config;