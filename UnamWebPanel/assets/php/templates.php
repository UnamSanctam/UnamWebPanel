<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once 'session-header.php';

function generalJSIncludes(){
    global $config;
    return "<script src='assets/modules/jquery/jquery.min.js'></script>
            <script src='assets/modules/jquery-confirm/jquery-confirm.js'></script>
            <script src='assets/modules/datatables/jquery.dataTables.min.js'></script>
            <script src='assets/modules/datatables-bs4/js/dataTables.bootstrap4.min.js'></script>
            <script src='assets/modules/datatables-responsive/js/dataTables.responsive.min.js'></script>
            <script src='assets/modules/datatables-responsive/js/responsive.bootstrap4.min.js'></script>
            <script src='assets/modules/datatables-buttons/js/dataTables.buttons.min.js'></script>
            <script src='assets/modules/datatables-buttons/js/buttons.bootstrap4.min.js'></script>
            <script src='assets/modules/datatables-buttons/js/buttons.html5.min.js'></script>
            <script src='assets/modules/datatables-buttons/js/buttons.print.min.js'></script>
            <script src='assets/modules/datatables-buttons/js/buttons.colVis.min.js'></script>
            <script src='assets/modules/select2/js/select2.min.js'></script>
            <script src='assets/modules/sweetalert2/sweetalert2.min.js'></script>
            <script src='assets/modules/izitoast/js/iziToast.min.js'></script>
            <script src='assets/modules/bootstrap/js/bootstrap.bundle.min.js'></script>
            <script src='assets/modules/overlayScrollbars/js/jquery.overlayScrollbars.min.js'></script>
            <script src='assets/modules/jquery-mousewheel/jquery.mousewheel.js'></script>
            <script src='assets/modules/raphael/raphael.min.js'></script>
            <script src='assets/modules/chartjs/chart.umd.js'></script>
            <script src='assets/modules/chartjs/chartjs-adapter-date-fns.bundle.min.js'></script>
            <script src='assets/js/adminlte.js'></script>
            <script src='__UNAM_LIB/unam_lib.js'></script>";
}

function generalCSSIncludes(){
    global $config;
    return "<link rel='stylesheet' href='assets/modules/fontawesome-free/css/all.min.css'>
            <link rel='stylesheet' href='assets/modules/izitoast/css/iziToast.css'>
            <link rel='stylesheet' href='assets/modules/jquery-confirm/jquery-confirm.css'>
            <link rel='stylesheet' href='assets/modules/select2/css/select2.min.css'>
            <link rel='stylesheet' href='assets/modules/sweetalert2/sweetalert2.min.css'>
            <link rel='stylesheet' href='assets/modules/overlayScrollbars/css/OverlayScrollbars.min.css'>
            <link rel='stylesheet' href='assets/modules/datatables-bs4/css/dataTables.bootstrap4.min.css'>
            <link rel='stylesheet' href='assets/modules/datatables-responsive/css/responsive.bootstrap4.min.css'>
            <link rel='stylesheet' href='assets/modules/datatables-buttons/css/buttons.bootstrap4.min.css'>
            <link rel='stylesheet' href='assets/css/adminlte.min.css'>
            <link rel='stylesheet' href='assets/css/bootstrap.custom.css'>
            <link rel='stylesheet' href='assets/css/custom.css'>";
}

function unamtFormGroup($content, $options=[]){
    $options = array_merge(['classes'=>'', 'extras'=>''], $options);

    return "<div class='form-group {$options['classes']}' {$options['extras']}>
                {$content}
            </div>";
}

function unamtInputGroup($content, $append=false, $options=[]){
    $options = array_merge(['classes'=>'', 'extras'=>''], $options);

    return "<div class='input-group".($append ? '-append' : '')." {$options['classes']}' {$options['extras']}>
                {$content}
            </div>";
}

function unamtInput($label, $id, $options=[]){
    $options = array_merge(['type'=>'text', 'classes'=>'', 'extras'=>'required', 'feedback'=>'please fill in '.$label, 'value'=>'', 'placeholder'=>'', 'appendIcon'=>''], $options);

    return "<label class='control-label'>{$label}</label>
            <input type='{$options['type']}' class='form-control {$options['classes']}' name='{$id}' value='{$options['value']}' placeholder='{$options['placeholder']}' {$options['extras']}>
            ".($options['appendIcon'] ? "
            <div class='input-group-append'>
                <div class='input-group-text'>
                    <span class='{$options['appendIcon']}'></span>
                </div>
            </div>
            " : '')."
            <div class='invalid-feedback'>
                {$options['feedback']}
            </div>";
}

function unamtSelect($label, $id, $content, $options=[]){
    $options = array_merge(['extras'=>'required', 'url'=>'', 'method'=>'', 'classes'=>''], $options);
    return (!empty($label) ? "<label>{$label}</label>" : '')."
            <select name='{$id}' class='form-control select2 hook-select2 {$options['classes']}' {$options['extras']} ".(!empty($options['url']) ? " data-url='{$options['url']}' " : '').(!empty($options['method']) ? " data-method='{$options['method']}' " : '').">
               {$content}
            </select>";
}

function unamtTextarea($label, $id, $content='', $options=[]){
    $options = array_merge(['type'=>'text', 'extras'=>'required', 'classes'=>''], $options);
    return "<label>{$label}</label>
            <textarea type='{$options['type']}' class='form-control {$options['classes']}' name='{$id}' {$options['extras']}>{$content}</textarea>";
}

function unamtSubmit($buttontext, $options=[]){
    $options = array_merge(['extras'=>'', 'classes'=>''], $options);
    return "<button type='submit' class='btn btn-primary btn-block {$options['classes']}' {$options['extras']}}>
                {$buttontext}
            </button>";
}

function unamtFormContainer($method, $file, $content, $options=[]){
    $options = array_merge(['classes'=>''], $options);
    return "<form action='#' class='needs-validation form-submit {$options['classes']}'>
                ".($method != false ? "<input type='hidden' name='method' value='{$method}'>" : '')."
                ".($file != false ? "<input type='hidden' class='file' value='{$file}'>" : '')."
                {$content}
            </form>";
}

function unamtHidden($name, $value){
    return "<input type='hidden' name='{$name}' value='{$value}'>";
}

function unamtToggleSwitch($checked, $options=[]){
    global $cf;
    $options = array_merge(['classes'=>''], $options);
    return "<label class='round-switch'>
              <input type='checkbox' class='{$options['classes']}' {$cf($checked ? 'checked' : '')}>
              <span class='round-slider'></span>
            </label>";
}

function unamtDatatable($id, $columnarray, $options=[]){
    global $cf;
    $options = array_merge(['minmode'=>'false', 'extradata'=>'{}', 'filters'=>'{}', 'classes'=>'', 'extra'=>''], $options);
    $columns = implode('</th><th>', $columnarray);
    function safeEncodeJSON($array, $default=''){
        if(!empty($array) && is_array($array)) {
            return json_encode($array);
        }
        return $default;
    }
    return "<div class='hook-datatable container-fluid {$options['classes']}' data-tableid='{$id}' data-minmode='{$options["minmode"]}' data-filters='{$cf(json_encode(['filters'=>[$options['filters']]]))}' data-extradata='{$options["extradata"]}' {$options['extra']}>
                    <table class='table table-bordered table-responsive table-hover' width='100%'' id='{$id}'>
                        <thead>
                        <tr>
                            <th>{$columns}</th>
                        </tr>
                        </thead>
                    </table>
                </div>";
}

function unamtCard($gridclasses, $headercontent, $bodyclass, $content, $options=[]){
    $options = array_merge(['cardclasses'=>'', 'cardextras'=>'', 'headerclasses'=>''], $options);
    return ($gridclasses != false ? "<div class='{$gridclasses}'>" : '')."
                <div class='card {$options['cardclasses']}' {$options['cardextras']}>
                    ".(!empty($headercontent) ? "<div class='card-header {$options['headerclasses']}'>
                        {$headercontent}
                    </div>" : '')."
                    <div class='card-body {$bodyclass}'>
                        {$content}
                    </div>
                </div>".
        ($gridclasses != false ? '</div>' : '');
}

function unamtAjaxButton($label, $method, $index, $options=[]){
    $options = array_merge(['classes'=>'btn-primary', 'extras'=>'', 'extradata'=>'{}'], $options);
    return "<a href='#' class='btn {$options["classes"]} ajax-action-confirm' data-method='{$method}' data-index='{$index}' data-extradata='{$options["extradata"]}' {$options["extras"]}>{$label}</a>";
}

function unamtSection($sectiontitle, $content, $options=[]){
    $options = array_merge(['backlink'=>'', 'extra'=>''], $options);
    return "<section class='section' {$options['extra']}>
                <div class='section-header'>
                    ".(empty($options['backlink']) ? '' :
                    "<div class='section-header-back'>
                      <a href='#' class='btn btn-icon nav-page' data-page='{$options['backlink']}'><i class='fas fa-arrow-left'></i></a>
                    </div>")."
                    <h1>{$sectiontitle}</h1>
                </div>
                <div class='section-body'>
                    {$content}
                </div>
            </section>";
}

function unamtRow($content, $options=[]){
    $options = array_merge(['classes'=>''], $options);
    return "<div class='row {$options['classes']}'>
                {$content}
            </div>";
}

function unamtMinerStatus($status){
    global $larr;
    switch($status){
        case 1:
            return unamtStatusColor('red', $larr['Stopped']);
        case 2:
            return unamtStatusColor('green', $larr['Active']);
        case 3:
            return unamtStatusColor('green', "{$larr['Active']} ({$larr['Idle']})");
        case 4:
            return unamtStatusColor('yellow', "{$larr['Paused']} ({$larr['Stealth']} - {REASON})");
        case 5:
            return unamtStatusColor('yellow', $larr['not_enough_free_vram']);
        case 6:
            return unamtStatusColor('yellow', $larr['Starting']);
        case 7:
            return unamtStatusColor('red', $larr['Error']);
        case -1:
            return unamtStatusColor('red', $larr['Offline']);
        default:
            return unamtStatusColor('red', $larr['Unknown']);
    }
}

function unamtTimeFormat($timeline, $shortform) {
    $periods = [($shortform ? 'd' : 'day') => 86400, ($shortform ? 'h' : 'hour') => 3600, ($shortform ? 'm' : 'minute') => 60, ($shortform ? 's' : 'second') => 1];
    $ret = "";
    if($timeline) {
        foreach ($periods as $name => $seconds) {
            $num = floor($timeline / $seconds);
            $timeline -= ($num * $seconds);
            if ($num > 0) {
                $ret .= ($shortform ? "{$num}{$name} " : "{$num} {$name}" . (($num > 1) ? 's' : '') . ' ');
            }
        }
    }
    return trim($ret);
}

function unamtStatusColor($color, $status){
    return "<span class='text-status-{$color}'>{$status}</span>";
}

function unamtFormatHashrate($num)
{
    $num = $num ?: 0;
    $units = ['H/s', 'KH/s', 'MH/s', 'GH/s', 'TH/s', 'PH/s', 'EH/s'];
    for ($i = 0; $num >= 1000; $i++) {
        $num /= 1000;
    }
    return round($num, 1)." {$units[$i]}";
}

function unamtChart($charttype, $chartconfig){
    return "<canvas class='hook-chart' data-chart-type='{$charttype}' data-chart-config='{$chartconfig}'></canvas>";
}

function templateHashrateChart($label, $data) {
    return unamtChart('hashrate', json_encode(['type'=>'bar', 'data'=>['datasets'=>[['label'=>$label, 'data'=>$data, 'fill'=>true]]], 'options'=>['responsive'=>true, 'scales'=>['x'=>['type'=>'time', 'max'=>date('Y-m-d H:i:00'), 'min'=>$data[0]['x'], 'time'=>['minUnit'=>'minute']], 'y'=>['ticks'=>['callback'=>''], 'min'=>0]]]]));
}

function templateDatatableTool($text, $checked, $options){
    return $text.' '.unamtToggleSwitch($checked, $options);
}

function templateLanguageSelect(){
    global $langID, $config;
    $options = '';
    foreach($config['languages'] as $key => $value) {
        $options .= "<option ".($langID == $key ? 'selected' : '')." value='{$key}'>{$value}</option>";
    }
    return unamtSelect('', 'language', $options, ['classes'=>'nav-lang']);
}

function templateExternalPage($title, $content, $options=[]){
    global $config;
    $options = array_merge(['containerclasses'=>'col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4', 'scriptcontent'=>''], $options);
    return "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title>{$title}</title>
  
  ".generalCSSIncludes()."
</head>
<body class='external-page'>
<div class='external-page-box'>
  {$content}
</div>

".generalJSIncludes()."

<script>
$('.unamLogin').on('submit', function(e){
    e.preventDefault();
    unam_jsonAjax('POST', 'auth-ajax.php', $(this).serialize(), function(data){
        location.reload();
    }, function(error){ iziToast.error({ title: 'Error', message: error, position: 'topRight' });
    });
});
</script>

{$options['scriptcontent']}

</body>
</html>
";
}

function templateDatatableX($datatable, $options=[]){
    global $larr, $config;
    require_once 'datatables.php';
    $table = &$datatables['tables'][$datatable];
    $etable = $table;
    $tabledata = [];
    foreach($etable['columns'] as $column){
        if(!isset($column['hidden']) || !$column['hidden']) {
            $tabledata['display'][] = $column['display'];
        }
    }
    $tools = templateDatatableTool($larr['auto_refresh'], false, ['classes'=>'refresh-datatables']);
    if($datatable == 'miner-table') {
        $tools = templateDatatableTool($larr['hide_offline_miners'], $_SESSION['hide_offline_miners'] ?? false, ['classes'=>'hide-offline-miners']).$tools;
    }

    return unamtCard('col-md-12', $etable['html_header']." <div class='card-tools'>{$tools}</div>", 'custom-tables',
        unamtDatatable($datatable, $tabledata['display'], $options)
    );
}
