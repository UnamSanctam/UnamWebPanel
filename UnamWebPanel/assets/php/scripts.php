<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once 'security.php';
?><script src="../assets/modules/jquery/jquery-3.7.1.min.js"></script>
<script src="../assets/modules/datatables/datatables.min.js"></script>
<script src="../assets/modules/select2/select2.min.js"></script>
<script src="../assets/modules/jquery-confirm/jquery-confirm.min.js"></script>
<script src="../assets/modules/izitoast/iziToast.min.js"></script>
<script src="../assets/modules/chartjs/chart.umd.min.js"></script>
<script src="../assets/modules/chartjs/chartjs-adapter-date-fns.bundle.min.js"></script>
<script src="../assets/js/pushmenu.js"></script>
<script src="../__UNAM_LIB/unam_lib.js"></script>
<script type="text/javascript" nonce="<?= $csp_nonce ?>">
    let datatables = [];
    let refreshTimer;

    function datatableTemplate(table, _data={}, _columnDefs=[]){
        return {
            dom: 'Blfrtip',
            paging: true,
            lengthMenu : [ [ 5, 10, 25, 50, -1 ], [ '5', '10', '25', '50', 'All' ] ],
            order: [[ 0, "desc" ]],
            stateSave: true,
            stateDuration: 0,
            processing: true,
            serverSide: true,
            <?php if($langID != 'en' && file_exists(dirname(__DIR__, 2)."/lang/datatables/{$langID}.json")){ echo "language: ".file_get_contents(dirname(__DIR__, 2)."/lang/datatables/{$langID}.json").","; } ?>
            ajax: {
                url: "../api/ajax-datatable.php",
                type: "POST",
                data: Object.assign({}, { tableid: table }, _data)
            },
            responsive: true,
            columnDefs: _columnDefs
        }
    }

    function successMessage(message){
        iziToast.success({ title: '<?php echo $larr['success']; ?>', message: message, position: 'topRight' });
    }

    function errorMessage(error){
        iziToast.error({ title: '<?php echo $larr['error']; ?>', message: error, position: 'topRight' });
    }

    function reloadDatatables(){
        for (let table in datatables) {
            datatables[table].ajax.reload(null, false);
        }
    }

    function formatHashrate(hashrate, precision=2) {
        let hr = parseFloat(hashrate);
        let unit= 'H/s';
        if(hr >= 1000) { hr /= 1000; unit= 'KH/s'; }
        if(hr >= 1000) { hr /= 1000; unit= 'MH/s'; }
        if(hr >= 1000) { hr /= 1000; unit= 'GH/s'; }
        if(hr >= 1000) { hr /= 1000; unit= 'TH/s'; }
        if(hr >= 1000) { hr /= 1000; unit= 'PH/s'; }
        return (hr.toFixed(precision) + ' ' + unit);
    }

    function renderCharts() {
        $('.hook-chart').each(function(i, obj) {
            let scope = $(this);
            let config = scope.data('chart-config');
            if(config){
                if(scope.data('chart-type') === 'hashrate') {
                    config.options.scales.y = { ticks: { callback: (label) => formatHashrate(label, 2) } };
                }
                config.options.plugins = { legend: { labels: { color: '#ffffff' } } };
                config.options.elements = { point: { radius: 0 } };
                new Chart(scope, config);
                scope.removeClass('hook-chart');
            }
        });
    }

    $(".nav-select").on('change', function(){
        const url = new URL(window.location.href);
        url.searchParams.set('id', $(this).val());
        window.location.href = url.toString();
    });

    $('.nav-lang').on('select2:select', function(e){
        unam_jsonAjax('POST', '../api/ajax-sitewide.php', { action: 'lang-change', newlangID: e.params.data.id }, function(){
            location.reload();
        }, function(error){ errorMessage(error); });
    });

    $('.refresh-datatables').on("click touch", function() {
        if($(this).is(':checked')){
            refreshTimer = setInterval(function(){
                reloadDatatables();
            },10000);
        }else{
            clearInterval(refreshTimer);
        }
    });

    $('.hide-offline-miners').on("click touch", function() {
        unam_jsonAjax('POST', '../api/ajax-actions.php', { action: 'miner-offline' }, function(){
            reloadDatatables();
        });
    });

    $(document).on('change', '.select-miner-config', function(){
        let scope = $(this);
        unam_jsonAjax('POST', '../api/ajax-actions.php', { action: 'miner-config', index: scope.data('index'), config: scope.val() }, function(data){
            if(data.successmsg){
                successMessage(data.successmsg);
            }
        }, function(error){ errorMessage(error); });
    }).on('submit', '.form-submit, .form-submit-reload, .form-submit-reset', function(e){
        e.preventDefault();
        let scope = $(this);
        unam_jsonAjax('POST', '../api/ajax-actions.php', scope.serialize(), function(data){
            if(scope.hasClass('form-submit-reload')){
                location.reload();
            }else{
                if(scope.hasClass('form-submit-reset')) {
                    scope[0].reset();
                }
                reloadDatatables();
                if(data.successmsg){
                    successMessage(data.successmsg);
                }
            }
        }, function(error){ errorMessage(error); });
    }).on('click touch', '.ajax-action, .ajax-action-confirm, .ajax-action-refresh', function(e){
        let scope = $(this);
        e.preventDefault();
        function ajaxAction(scope){
            unam_jsonAjax('POST', '../api/ajax-actions.php', Object.assign({}, {
                action: scope.data('action'),
                index: scope.data('index')
            }, scope.data('extradata')), function (data) {
                if(scope.hasClass('ajax-action-reload')){
                    location.reload();
                }
                if(scope.hasClass('ajax-action-refresh')){
                    reloadDatatables();
                }
                if(data.successmsg){
                    successMessage(data.successmsg);
                }
            }, function (error) {
                errorMessage(error);
            });
        }

        if(scope.hasClass('ajax-action-confirm')){
            $.confirm({
                title: "<?php echo $larr['are_you_sure']; ?>",
                content: "<?php echo $larr['not_reversible']; ?>",
                icon: 'fa fa-warning',
                buttons: {
                    continue: {
                        text: '<?php echo $larr['continue']; ?>',
                        btnClass: 'btn-blue',
                        action: function () {
                            ajaxAction(scope);
                        }
                    },
                    cancel: {
                        text: '<?php echo $larr['cancel']; ?>',
                        btnClass: 'btn-danger'
                    }
                }
            });
        }else{
            ajaxAction(scope);
        }
    }).on('click touch', ".hashrate-history", function(e){
        e.preventDefault();
        let scope = $(this);
        const index = scope.data('index');
        if(index){
            unam_jsonAjax('POST', '../api/ajax-actions.php', {
                action: 'miner-history',
                index: index
            }, function (data) {
                scope.parent().html(data.successmsg);
                renderCharts();
            }, function (error) {
                errorMessage(error);
            });
        }
    });

    $(".select2").select2();

    $('.hook-datatable').each(function() {
        let scope = $(this);
        if(scope.data('tableid')){
            let columnDef = [];
            datatables[scope.data('tableid')] = $('#'+scope.data('tableid')).DataTable(datatableTemplate(scope.data('tableid'), scope.data('extradata'), columnDef));
        }
    });

    renderCharts();
</script>