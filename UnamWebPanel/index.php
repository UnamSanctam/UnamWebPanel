<?php
require_once 'assets/php/safety-check.php';
require_once 'security.php';
require_once 'assets/php/templates.php';



$page = $base->unam_filterParameter('page');

$loadurl = $page ?: 'miners';
?><!DOCTYPE html>
<html lang="<?php echo $langID; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unam Web Panel | Unam Sanctam</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <?php echo generalCSSIncludes(); ?>
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="index.php" class="brand-link">
            <img src="assets/img/unam.png" alt="Unam Sanctam" class="brand-image">
            <span class="brand-text font-weight-light">Unam Web Panel</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <?php echo templateLanguageSelect(); ?>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-container" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="" class="nav-link nav-page" data-page="miners">
                            <i class="nav-icon fas fa-network-wired"></i>
                            <p>
                                <?php echo $larr['Miners']; ?>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link nav-page" data-page="configurations">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                <?php echo $larr['Configurations']; ?>
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="main-content ajaxcontainer">

                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2021-<?php echo date("Y"); ?> <a href="https://github.com/UnamSanctam">Unam Sanctam</a>.</strong>
        For educational purposes.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> <?php echo $config["unam_version"] ?>
        </div>
    </footer>
</div>

<?php echo generalJSIncludes(); ?>

<script type="text/javascript">
let openLoadUrl = "<?php echo $loadurl; ?>";
let paramID =  "<?php echo $base->unam_filterParameter('id'); ?>";
let lastPage = '';

let showLoader = false;
let datatables = [];

let refreshTimer;

$(document).ready(function()
{
  loadPageContentAjax(this, 'GET', openLoadUrl, {'id': paramID});
}).on("click touch", '.nav-page', function(e)
{
  e.preventDefault();
  paramID = "";
  let scope = $(this);
  if(scope.hasClass('nav-link')){
      $('.nav-container li a').removeClass('active');
      scope.addClass('active');
  }
  loadPageContentAjax(this, 'GET', scope.data('page'), '');
}).on('change', ".nav-select", function(e){
    let scope = $(this);
    loadPageContentAjax(this, 'GET', scope.data('page'), { id: scope.val() });
}).on('select2:select', '.nav-lang', function(e){
    unam_jsonAjax('POST', 'api/ajax-sitewide.php', {method: 'lang-change', newlangID: e.params.data.id}, function(data){
        location.reload();
    }, function(error){ errorMessage(error); });
}).on('change', '.select-miner-config', function(e){
    let scope = $(this);
    unam_jsonAjax('POST', 'api/ajax-actions.php', Object.assign({}, {method: scope.data('method'), index: scope.data('index'), config: scope.val()}, scope.data('extradata') || {} ), function(data){
        if(data.successmsg){
            successMessage(data.successmsg);
        }
    }, function(error){ errorMessage(error); });
}).on("click touch", '.refresh-datatables', function(e)
{
    if($(this).is(':checked')){
        refreshTimer = setInterval(function(){
            reloadDatatables();
            console.log("reloaded");
        },10000);
    }else{
        clearInterval(refreshTimer);
    }
});

function datatableTemplate(table, _data={}, minmode=false, _columnDefs=[]){
    return {
        dom: minmode ? 't' : 'Blfrtip',
        paging: !minmode,
        lengthMenu : [[5, 10, 25, 50, -1 ],['5', '10', '25', '50', 'All' ]],
        order: [[ 0, "desc" ]],
        stateSave: true,
        stateDuration: 0,
        processing: true,
        serverSide: true,
        sScrollX: "100%",
        <?php if($langID != 'en' && file_exists(__DIR__."/lang/datatables/{$langID}.json")){ echo "language: ".file_get_contents(__DIR__."/lang/datatables/{$langID}.json").","; } ?>
        ajax: {
            url: "api/custom-table.php",
            type: "POST",
            data: Object.assign({}, {method: 'datatable-get', tableid: table, cid: paramID}, _data)
        },
        responsive: true,
        columnDefs: _columnDefs
    };
}

function confirmationBox(code){
    Swal.fire({
        title: "<?php echo $larr['are_you_sure']; ?>",
        text: "<?php echo $larr['not_reversible']; ?>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: "<?php echo $larr['Continue']; ?>",
        cancelButtonText: "<?php echo $larr['Cancel']; ?>",
        hideClass: {
            popup: '',
        },
        showClass: {
            popup: '',
        }
    })
        .then((confirmation) => {
            if(confirmation.value){
                code();
            }
        });
}

function successMessage(message){
    iziToast.success({ title: '<?php echo $larr['Success']; ?>', message: message, position: 'topRight' });
}

function errorMessage(error){
    iziToast.error({ title: '<?php echo $larr['Error']; ?>', message: error, position: 'topRight' });
}

function reloadDatatables(){
    for (let table in datatables) {
        datatables[table].ajax.reload(null, false);
    }
}

$(document).on('submit', ".form-submit", function(e){
    e.preventDefault();
    let scope = $(this);
    unam_jsonAjax('POST', scope.find('.file').val(), scope.serialize(), function(data){
        if(scope.hasClass('form-page-reload')){
            location.reload();
        }else{
            Swal.close();
            if(scope.hasClass('form-page-refresh')){
                loadPageContentAjax(this, 'GET', lastPage, {'id': paramID });
            }
            reloadDatatables();
            if(data.successmsg){
                successMessage(data.successmsg);
            }
        }
    }, function(error){ errorMessage(error); });
}).on('click touch', ".ajax-action-confirm, .ajax-action, .ajax-action-checkbox", function(e){
    let scope = $(this);
    if(!scope.hasClass('ajax-action-checkbox')){
        e.preventDefault();
    }
    function ajaxAction(scope){
        let indexval = (scope.data('index') ? scope.data('index') : scope.closest('tr').find('td').html());
        let extradata = Object.assign({}, (scope.hasClass('ajax-action-checkbox') ? {checked: scope.is(':checked') ? 1 : 0} : {}), scope.data('extradata'));
        unam_jsonAjax('POST', 'api/ajax-actions.php', Object.assign({}, {
            method: scope.data('method'),
            index: indexval
        }, extradata), function (data) {
            if(scope.hasClass('ajax-action-reload')){
                loadPageContentAjax(this, 'GET', lastPage, {'id': paramID });
            }
            reloadDatatables();
            if(data.successmsg){
                successMessage(data.successmsg);
            }
        }, function (error) {
            errorMessage(error);
        });
    }

    if(scope.hasClass('ajax-action-confirm')){
        confirmationBox(function(){
            ajaxAction(scope);
        });
    }else{
        ajaxAction(scope);
    }
});

function pushHistory(_history, _url, _data){
    lastPage = _url;
    if(_data.id){
        paramID = _data.id;
    }
    if(_history) {
        history.pushState({url: _url, data: _data}, "", '?' + $.param(Object.assign({}, {page: _url}, _data)));
    }

}

window.addEventListener('popstate', function (e) {
    if (e.state != null ){
        loadPageContentAjax(this, 'GET', e.state.url, e.state.data, '.ajaxcontainer', false);
    }
    return true;
});

function loadPageContentAjax(scope, _type, _url, _data={}, _container='.ajaxcontainer', _history=true) {
    showLoader = true;
    window.setTimeout(function() {
        if(showLoader) {
            $(_container).html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
        }
    }, 200);
    jQuery.ajax(
      {
          type: _type,
          url: 'page-loader.php',
          dataType: 'html',
          headers: {
              'UNAM-Request-Type': 'AJAX'
          },
          data: $.param(Object.assign({}, _data, {page: _url})),
          success: function (data) {
              showLoader = false;
              if(data.substring(0, 2) === '{"'){
                  let datajson = JSON.parse(data);
                  if(datajson.sessionExpired)
                  {
                      location.reload();
                  }
                  else if(datajson.redir)
                  {
                      paramID = "";
                      loadPageContentAjax(this, 'GET', datajson.redir);
                  }
              }else{
                  datatables = [];

                  $(_container).html(data);

                  $(".select2").select2();

                  $('.hook-datatable').each(function(i, obj) {
                      let scope = $(this);
                      if(scope.data('tableid')){
                          let editColumns = scope.data('editcolumns');
                          let columnDef = [];
                          if(editColumns) {
                              Object.keys(editColumns).forEach(key => {
                                  columnDef.push({sName: editColumns[key], targets: Number(key)});
                              });
                          }
                          datatables[scope.data('tableid')] = $('#'+scope.data('tableid')).DataTable(datatableTemplate(scope.data('tableid'), Object.assign({}, scope.data('extradata'), scope.data('filters')), scope.data('minmode'), columnDef));
                          if(editColumns){
                              datatableEditable(datatables[scope.data('tableid')], Object.keys(editColumns).map(Number), (scope.data('editformat') ? scope.data('editformat') : [{}]));
                          }
                      }
                  });

                  $('.hook-select2').each(function(i, obj) {
                      let scope = $(this);
                      if(scope.data('url') && scope.data('method')){
                          scope.select2({
                              ajax: {
                                  url: scope.data('url'),
                                  type: "GET",
                                  dataType: 'JSON',
                                  delay: 250,
                                  data: function (params) {
                                      return {
                                          searchTerm: params.term,
                                          method: scope.data('method'),
                                          page: params.page || 1
                                      };
                                  },
                                  processResults: function (data, params) {
                                      var page = params.page || 1;
                                      return {
                                          pagination: {
                                              more: (page * 10) <= data[0].total_count
                                          },
                                          results: $.map(data.slice(1), function (item) { return {id: item.id, text: item.text}})
                                      };
                                  },
                                  cache: true
                              }
                          });
                      }
                  });

                  pushHistory(_history, _url, _data);
              }
          }
      });
}
</script>
</body>
</html>