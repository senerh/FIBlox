<?php
include (__DIR__."/../../../inc/includes.php");

Html::header('Mon plugin',$_SERVER['PHP_SELF'],"plugins","example","about");

echo 'À propos du plugin<br />';

Html::footer();
?>