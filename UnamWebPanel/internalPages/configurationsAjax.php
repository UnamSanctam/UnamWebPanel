<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once dirname(__DIR__, 1).'/security.php';
require_once dirname(__DIR__, 1).'/assets/php/templates.php';
$configID = getParam('id') ?: 1;

$configs = $base->unam_dbSelect(getConn(), 'configs', 'cf_configID, cf_name, cf_data', [], 0, 1);

$configoptions = '';
$currentconfig = [];
if($configs) {
    foreach ($configs as $configdata) {
        $configoptions .= "<option value='{$configdata['cf_configID']}' " . ($configdata['cf_configID'] == $configID ? 'selected' : '') . ">{$configdata['cf_name']}</option>";
        if($configdata['cf_configID'] == $configID){
            $currentconfig = $configdata;
        }
    }
}

function getConfigValue($key) {
    global $currentconfig;
    return $currentconfig[$key] ?? '';
}

echo unamtSection($larr['Configurations'],
    unamtRow(
        unamtCard(4, "<h4>{$larr['Add']} {$larr['Configuration']}</h4>", '',
            unamtFormContainer('config-add', 'api/ajax-actions.php',
                unamtFormGroup(unamtInput($larr['Name'], 'name')).
                unamtFormGroup(unamtTextarea("{$larr['Configuration']} JSON", 'data', "", ['extras'=>''])).
                unamtFormGroup(unamtSubmit($larr['Add']))
            , ['classes'=>'form-page-refresh'])
        ).
        unamtCard(4, "<h4>{$larr['Edit']} {$larr['Configuration']}</h4>", '',
            unamtFormGroup(unamtSelect("{$larr['Choose']} {$larr['Configuration']}", 'config', $configoptions, ['classes'=>'nav-select', 'extras'=>"data-page='configurations'"])).
            unamtFormContainer('config-update', 'api/ajax-actions.php',
                unamtFormGroup(unamtHidden('index', getConfigValue('cf_configID'))).
                unamtFormGroup(unamtTextarea("{$larr['Configuration']} JSON", 'data', getConfigValue('cf_data'), ['extras'=>''])).
                unamtRow(
                    unamtFormGroup(unamtSubmit($larr['Save']), ['classes'=>'col']).
                    unamtFormGroup(unamtAjaxButton($larr['Remove'], 'config-remove', $configID, ['classes'=>($configID == 1 || $configID == 2 ? 'disabled ' : '').' col btn-danger ajax-action-reload']), ['classes'=>'col'])
                )
            )
        )
    )
);
