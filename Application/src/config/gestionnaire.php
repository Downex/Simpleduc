<?php
function gestionnaire($gestion){
    $r = true;
    if($_SESSION['config']['mode'] == 'dev'){
        if ($gestion->errorCode()!=0){
           print_r($gestion->errorInfo()); 
           $r = false;
        }
    }    
    return $r;
}
?>