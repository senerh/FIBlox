<?php
// Hook called on profile change
// Good place to evaluate the user right on this plugin
// And to save it in the session
function plugin_change_profile_fiblox()
{
	// For fiblox : same right of computer
	if (Session::haveRight('computer','w'))
	{
		$_SESSION["glpi_plugin_fiblox_profile"] = array('fiblox' => 'w');
	}
	else if (Session::haveRight('computer','r'))
	{
		$_SESSION["glpi_plugin_fiblox_profile"] = array('fiblox' => 'r');
	}
	else
	{
		unset($_SESSION["glpi_plugin_fiblox_profile"]);
	}
}

// procédure d'installation du plugin
// c'est ici qu'on créé notament les tables
function plugin_fiblox_install()
{
	global $DB;

	if (!TableExists("glpi_plugin_fiblox_data"))
	{
		$query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_fiblox_data` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`avancement` int(11) NOT NULL,
				`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";

		$DB->query($query) or die("error creating glpi_plugin_fiblox_data ". $DB->error());

		$query = "INSERT INTO `glpi_plugin_fiblox_data` (`id`, `avancement`, `date`) VALUES
				(1, 100, '2014-04-30 11:11:25');";
		$DB->query($query) or die("error populate glpi_plugin_fiblox ". $DB->error());
	}
	
	if (!TableExists("glpi_plugin_fiblox_configuration"))
	{
		$query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_fiblox_configuration` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`ip` varchar(255) NOT NULL,
		`user` varchar(255) NOT NULL,
		`password` varchar(255) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

		$DB->query($query) or die("error creating glpi_plugin_fiblox_data ". $DB->error());

		$query = "INSERT INTO `glpi_plugin_fiblox_configuration` (`id`, `ip`, `user`, `password`) VALUES (1, '127.0.0.1', 'admin', 'admin');";
		$DB->query($query) or die("error populate glpi_plugin_fiblox ". $DB->error());
	}
	
	//cron
	$cron = new CronTask;
	if (!$cron->getFromDBbyName('PluginFibloxSynchro','Synchronize'))
	{
		CronTask::Register('PluginFibloxSynchro', 'Synchronize', DAY_TIMESTAMP,array('param' => 24));
	}
	return true;
}

// procédure de désinstallation du plugin
// c'est ici qu'on supprime entre autre les tables crées
function plugin_fiblox_uninstall()
{
	global $DB;
	
	if (TableExists("glpi_plugin_fiblox_data"))
	{
		$query = "DROP TABLE `glpi_plugin_fiblox_data`";
		$DB->query($query) or die("error deleting glpi_plugin_fiblox_data");
	}
	
	if (TableExists("glpi_plugin_fiblox_configuration"))
	{
		$query = "DROP TABLE `glpi_plugin_fiblox_configuration`";
		$DB->query($query) or die("error deleting glpi_plugin_fiblox_configuration");
	}
	
	$cron = new CronTask;
	if ($cron->getFromDBbyName('PluginFibloxSynchro','Synchronize'))
	{
		CronTask::Unregister('PluginFibloxSynchro');
	}
	return true;
}
?>