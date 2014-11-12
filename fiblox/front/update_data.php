<?php
include(__DIR__.'/fonctions.php');

if (getAvancement() == 100 && isConfigured())
{
	setAvancement(0);
	exec('php update_script.php > /dev/null &');
	header('Location: ../front/fiblox.php');
}
else
{
	echo 'ECHEC ! La connexion au serveur InfoBlox n \'est pas configuree ou une synchronisation est deja en cours. ';
}

?>
