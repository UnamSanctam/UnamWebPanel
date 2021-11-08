<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__, 1).'/assets/php/templates.php';

echo unamtSection($larr['Miners'],
    unamtRow(
        templateDatatableX('miner-table')
    )
);
