<?php
include (__DIR__."/../../../inc/includes.php");

Html::header('FIBlox',$_SERVER['PHP_SELF'],"plugins","fiblox","about");

echo '<h2>À propos</h2>';
echo '<strong>FIBlox</strong> a été développé dans le cadre dun stage de DUT Informatique au sein de la DSI de l\'UPMF.<br />';
echo 'La documentation ainsi que le plugin sont disponibles <a href="https://github.com/senerh/FIBlox">à cette adresse.</a><br />';

Html::footer();
?>