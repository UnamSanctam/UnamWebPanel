<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
$config = [];

/* Change UnamSancam below to change the password. */
$config['password'] = 'UnamSanctam';

/*
 * If your web server uses Apache or IIS then there is no need to
 * change the db file location since the .htaccess or web.config file
 * will protect the database file from being accessed.
 * 
 * You can test if you are vulnerable by trying to access
 * WEBSITEURL/unamwebpanel.db (replace WEBSITEURL with your URL),
 * if it returns a 403 Forbidden error then you are protected
 */
$config['db_file'] = dirname(__FILE__)."/db/unamwebpanel.db";

$config['failedlogin_blocktime'] = 15;
$config['failedlogin_blocktries'] = 5;

$config['api_minreportime'] = 50;

$config['languages'] = ['en', 'sv', 'fr', 'de', 'pl', 'ua'];

$config['unam_version'] = "1.6.0";

global $config;
