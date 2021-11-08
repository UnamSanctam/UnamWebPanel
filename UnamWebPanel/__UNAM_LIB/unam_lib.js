/* Made by Unam Sanctam https://github.com/UnamSanctam */

let universalerror = "Error: Something went wrong.";

function unam_jsonAjax(_type, _url, _data, success=function(response){}, failure=function(error){}){
    $.ajax({
        type: _type,
        url: _url,
        dataType: 'json',
        headers: {
            'UNAM-Request-Type': 'AJAX'
        },
        data: _data,
        complete: function(data){
            if(data.responseJSON && data.responseJSON.sessionExpired && data.responseJSON.sessionExpired){
                location.reload();
            }else if(data.responseJSON && data.responseJSON.response && data.responseJSON.response === "success"){
                success(data.responseJSON);
            }else{
                var error = (data.responseJSON && data.responseJSON.errormsg ? data.responseJSON.errormsg : universalerror);
                failure(error);
            }
        }
    });
}