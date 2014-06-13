<?php
// initialise les hook du plugin
function plugin_init_fiblox()
{
	global $PLUGIN_HOOKS;

	// Display a menu entry ?
	if (isset($_SESSION["glpi_plugin_fiblox_profile"]))// Right set in change_profile hook
	{
		//page d'acceuil
		$PLUGIN_HOOKS['menu_entry']['fiblox'] = 'front/fiblox.php';
		$PLUGIN_HOOKS["helpdesk_menu_entry"]['fiblox'] = true;
		
		$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['home']['title'] = 'Accueil';
		$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['home']['page']  = '/plugins/fiblox/front/fiblox.php';

		$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['home']['links']['Actualiser les données'] = '/plugins/fiblox/front/update_data.php';
		$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['home']['links']['À propos'] = '/plugins/fiblox/front/about.php';
		//$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['home']['links']['add']    = '/plugins/fiblox/front/fiblox.form.php';
		//$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['home']['links'][__s('Test link', 'fiblox')] = '/plugins/fiblox/index.php';
		//$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['home']['links']['config'] = '/plugins/fiblox/index.php';
		
		//à propos
		$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['about']['title'] = 'À propos';
		$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['about']['page']  = '/plugins/fiblox/front/about.php';
		
		//config
		$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['config']['title'] = 'Configuration';
		$PLUGIN_HOOKS['submenu_entry']['fiblox']['options']['config']['page']  = '/plugins/fiblox/config.php';
		
		//jquery
		$PLUGIN_HOOKS['add_javascript']['fiblox']['jquery'] = 'ajax/jquery-1.10.2.js';
		
		//java		
		$PLUGIN_HOOKS['add_javascript']['fiblox']['progressbar'] = 'ajax/progressbar.js';
		
		//css
		$PLUGIN_HOOKS['add_css']['fiblox']['progressbar'] = 'css/progressbar.css';
	}

	// page de configuration
	if (Session::haveRight('config','w'))
	{
		$id=$PLUGIN_HOOKS['config_page']['fiblox'] = 'config.php';
		exec("echo $id > monid.txt");
	}

	// nécessaire pour la sécurité
	$PLUGIN_HOOKS['csrf_compliant']['fiblox'] = true;
	
	// Change profile
	$PLUGIN_HOOKS['change_profile']['fiblox'] = 'plugin_change_profile_fiblox';
	
	Plugin::registerClass('PluginFibloxSynchro');
}


// informations sur le plugin
function plugin_version_fiblox()
{
	return array(
	'name'           => 'FIBlox',
	'version'        => '0.84+1.0',
	'author'         => 'Hakan SENER',
	'license'        => 'GPLv2+',
	'homepage'       => 'http://www.upmf-grenoble.fr/',
	'minGlpiVersion' => '0.84');
}

// la version de GLPI doit être 0.84.X
function plugin_fiblox_check_prerequisites()
{
	if (version_compare(GLPI_VERSION,'0.84','lt') || version_compare(GLPI_VERSION,'0.85','gt'))
	{
		echo "This plugin requires GLPI 0.84.X";
		return false;
	}
	return true;
}

// Check configuration process for plugin : need to return true if succeeded
// Can display a message only if failure and $verbose is true
function plugin_fiblox_check_config($verbose=false)
{
	if (true)// Your configuration check
	{
		return true;
	}

	if ($verbose)
	{
		echo 'Installed / not configured';
	}
	return false;
}
?>
