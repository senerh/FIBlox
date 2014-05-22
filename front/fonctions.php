<?php
include_once(__DIR__.'/../../../inc/includes.php');

function get_IP_range($strIP)
{
	list($calcul_adresse_ip, $calcul_mask) = explode("/", $strIP);
	$calcul_mask = intval($calcul_mask);
	
	// Validation du champs IP
	$calcul_inetaddr=ip2long($calcul_adresse_ip);

	// Conversion du masque
	$calcul_chaine_mask = (string) long2ip(256*256*256*256 - pow(2, 32 - $calcul_mask));

	// Calcul du nombre de HOST
	if ($calcul_mask==32)
		$calcul_host=1;
	else
		$calcul_host=pow(2,32-$calcul_mask)-2;

	// Calcul de la route
	$calcul_route=$calcul_inetaddr&ip2long($calcul_chaine_mask); // Ajoute l'IP et le masque en binaire
	$calcul_route=long2ip($calcul_route); // Convertit l'adresse inetaddr en IP

	// Calcul de la premiere adresse
	if ($calcul_mask==32)
		$offset=0;
	else
		$offset=1;

	if ($calcul_mask==31)
		$calcul_premiere_ip="N/A";
	else
	{
		$calcul_premiere_ip=ip2long($calcul_route)+$offset;
		$calcul_premiere_ip=long2ip($calcul_premiere_ip);
	}

	// Calcul de la dernière adresse
	if ($calcul_mask==32)
		$offset=-1;
	else
		$offset=0;

	if ($calcul_mask==31)
		$calcul_derniere_ip="N/A";
	else
	{
		$calcul_derniere_ip=ip2long($calcul_route)+$calcul_host+$offset;
		$calcul_derniere_ip=long2ip($calcul_derniere_ip);
	}
	
	return array($calcul_premiere_ip, $calcul_derniere_ip);
}

function getAvancement()
{
	global $DB;

	$rq = "SELECT avancement FROM `glpi`.`glpi_plugin_example_data`;";
	$reponse = $DB->query($rq);
	$avancement = $reponse->fetch_assoc();
	$avancement = $avancement['avancement'];
	$reponse->free();
	return $avancement;
}

function setAvancement($avancement)
{
	global $DB;

	$DB->query("UPDATE `glpi`.`glpi_plugin_example_data` SET `avancement` = '$avancement' WHERE `glpi_plugin_example_data`.`id` = 1;");
}

function getUpdateDate()
{
	global $DB;

	$rq = "SELECT date FROM `glpi`.`glpi_plugin_example_data`;";
	$reponse = $DB->query($rq);
	$date = $reponse->fetch_assoc();
	$date = $date['date'];
	$reponse->free();

	return date("d/m/Y H:i:s", strtotime($date));
}

function setUpdateDate()
{
	global $DB;
	
	$rq = "UPDATE `glpi`.`glpi_plugin_example_data` SET `date` = NOW() WHERE `glpi_plugin_example_data`.`id` = 1;";

	$DB->query($rq);
}

function getConfiguration()
{
	global $DB;
	
	$rq = "SELECT * FROM `glpi`.`glpi_plugin_example_configuration`;";
	$reponse = $DB->query($rq);
	
	$data = $reponse->fetch_assoc();
	$ip = $data['ip'];
	$user = $data['user'];
	$password = $data['password'];
	
	$reponse->free();
	
	return array($ip, $user, $password);
}

function isConfigured()
{
	global $DB;
	
	$rq = "SELECT * FROM `glpi`.`glpi_plugin_example_configuration`;";
	$reponse = $DB->query($rq);
	
	$data = $reponse->fetch_assoc();
	$ip = $data['ip'];
	$user = $data['user'];
	$password = $data['password'];
	
	$reponse->free();
	
	$cmd = "perl ".__DIR__."/testconn.pl -s $ip -u $user -p $password -e 'show network $networkIP' > ".__DIR__."/res3.txt";
	
	exec($cmd);
	$fichier = file_get_contents(__DIR__.'/res3.txt');
	
	if (strcmp("OK", $fichier) == 0)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

function getEntityID($entityName)
{
	global $DB;

	$rq = "SELECT id FROM `glpi`.`glpi_entities` WHERE `glpi_entities`.`name` = '$entityName';";
	$reponse = $DB->query($rq);
	
	$data = $reponse->fetch_assoc();
	
	if ($data == false)
	{
		return -1;
	}
	else
	{
		return $data['id'];
	}
	$reponse->free();
}

function getArrayEntity($networkIP)
{
	$tab = getConfiguration();
	$ip = $tab[0];
	$user = $tab[1];
	$password = $tab[2];
	$cmd = "perl ".__DIR__."/ibcli -s $ip -u $user -p $password -e 'show network $networkIP' > ".__DIR__."/res2.txt";
	exec($cmd);
	$fichier = file_get_contents(__DIR__.'/res2.txt');
	
	//getSite
	$chaine = "extensible_attributes : GLPI-Entity=";
	$pos = strpos($fichier, $chaine);
	if ($pos === false)
	{
		$site = 'R-Erreur';
	}
	else
	{
		$posMot = $pos+strlen($chaine);

		$site = '';
		while ($fichier[$posMot] != "\n")
		{
			$site = $site . $fichier[$posMot];
			$posMot = $posMot + 1;
		}
	}
	$tabSite = explode(",", $site);
	
	$tabRes = array();
	
	foreach ($tabSite as $IB_Entity)
	{
		$entity = getEntityID($IB_Entity);
		//on met l'entite root si l'entite n'est pas reconnue
		if ($entity == -1)
		{
			$entity = 0;
		}
		$IB_Entity = substr($IB_Entity,2);
		array_push($tabRes, array("name" => "$IB_Entity", "entity" => "$entity"));
	}
	return $tabRes;
}
?>