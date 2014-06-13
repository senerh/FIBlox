<?php
include (__DIR__."/../../../inc/includes.php");

Html::header('FIBlox',$_SERVER['PHP_SELF'],"plugins","fiblox","about");

echo '<p style="text-align: center;">';
echo '<strong>FIBlox</strong> a été développé dans le cadre d\'un stage de DUT Informatique au sein de la DSI de l\'UPMF.<br />';
echo 'La documentation ainsi que le plugin sont disponibles <a href="https://github.com/senerh/FIBlox">à cette adresse.</a><br />';
echo '</p>';
Html::footer();
?>