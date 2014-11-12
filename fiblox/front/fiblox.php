<?php
include(__DIR__.'/../../../inc/includes.php');

include(__DIR__.'/fonctions.php');

if ($_SESSION["glpiactiveprofile"]["interface"] == "central")
{
	Html::header('FIBlox', $_SERVER['PHP_SELF'],"plugins","fiblox","home");
}
else
{
	Html::helpHeader('FIBlox', $_SERVER['PHP_SELF']);
}

if (getAvancement() == 100)
{
	$date = getUpdateDate();
	echo "Denière mise à jour : <strong>$date</strong>.";
}
elseif (getAvancement() != 100)
{
        $av = getAvancement();
        echo " <strong> Mise à jour en cours ...(".$av." %) </strong> ";
}
/*else
{
	echo '<div id="progress">';
	echo '<div id="progressbar"><div></div></div>';
	echo '<div id="text"></div>';
	echo '</div>';
}*/
Search::show('PluginFusioninventoryIPRange');

Html::footer();
?>
