<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
$config = [];

$config['password'] = 'UnamSanctam';

/*
 * If your web server uses Apache then there is no need to
 * change the db location since the .htaccess file will 
 * protect the database file from being accessed.
 * 
 * You can test if you are vulnerable by trying to access
 * WEBSITEURL/unamwebpanel.db (replace WEBSITEURL with your URL),
 * if it returns a 403 Forbidden error then you are protected
 */
 $config['db_file'] = dirname(__FILE__)."/unamwebpanel.db";

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

$config['unam_version'] = "1.1";

global $config;