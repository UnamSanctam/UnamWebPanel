<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__, 1).'/security.php';
require_once dirname(__DIR__, 1).'/assets/php/templates.php';

echo unamtSection($larr['Miners'],
    unamtRow(
        templateDatatableX('miner-table')
    ).
    unamtRow(
        unamtCard('col-md-4', "<h4>{$larr['remove_offline_miners']}</h4>", '',
            unamtFormContainer('miner-clean', 'api/ajax-actions.php',
                unamtFormGroup(unamtInput("{$larr['minimum_days_offline']}:", 'amount', ['type'=>'number', 'value'=>'1', 'extras'=>'min="1" required'])).
                unamtFormGroup(unamtSubmit($larr['Remove'], ['classes'=>'col btn-danger']))
            )
        )
    )
);
