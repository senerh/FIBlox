<?php
class PluginFibloxSynchro extends CommonDBTM
{
	static function cronSynchronize()
	{
		$cmd = "php ".__DIR__."/../front/update_script.php > /dev/null &";
		exec($cmd);
	}
}
?>