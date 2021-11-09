<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
require_once 'assets/php/templates.php';

if($loggedin){
    header("Location: index.php");
}

echo templateExternalPage("Unam Web Panel &mdash; {$larr['Login']}",
    unamtCard(false, "<h4><b>Unam</b> Web Panel</h4>", '',
        unamtFormContainer(null, null,
            unamtHidden('method', 'Login').
            unamtHidden('csrf_token', $_SESSION['csrf_token']).
            $larr['Password'].
            unamtFormGroup(unamtInputGroup(unamtInput("", 'password', ['type'=>'password', 'appendIcon'=>'fas fa-lock', 'placeholder'=>$larr['Password']]), ['classes'=>'mb-3'])).
            unamtSubmit($larr['Login'], ['classes'=>'btn-primary'])
            , ['classes'=>'unamLogin'])
    , ['cardclasses'=>'card-dark card-outline card-primary', 'headerclasses'=>'text-center'])
);