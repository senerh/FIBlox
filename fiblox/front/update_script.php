<?php
include(__DIR__.'/../../../inc/includes.php');
include(__DIR__.'/fonctions.php');

if (isConfigured() == 1)
{
	$tab = getConfiguration();
	$ip = $tab[0];
	$user = $tab[1];
	$password = $tab[2];
	exec("perl ".__DIR__."/ibcli -s $ip -u $user -p $password -e 'show network shared' > ".__DIR__."/res.txt");

	$tabFichier = file(__DIR__.'/res.txt');
	$total = count($tabFichier);
	//on ignore la dernière ligne vide
	$total--;
	
	$tabRQ = array();

	//on ignore la premiere ligne
	for($i=1; $i<=$total; $i++)
	{
		$tab = explode(":", $tabFichier["$i"]);
		$nomVlan = substr($tab[0],2);
		$strNetwork = substr($tab[1],1);
		$strNetwork = substr($strNetwork,0,-1);
		$tabNetwork = explode(" ", $strNetwork);
		foreach($tabNetwork as $network)
		{
			$data = getArrayEntity($network);
			foreach($data as $subnet)
			{
				$entity = $subnet['entity'];
				list($debut, $fin) = get_IP_range($network);
				$nomReseau = $subnet['name'].'-'.$nomVlan.'@'.$network;
				array_push($tabRQ, "INSERT INTO `glpi`.`glpi_plugin_fusioninventory_ipranges` (`id`, `name`, `entities_id`, `ip_start`, `ip_end`) VALUES (NULL, '$nomReseau', '$entity', '$debut', '$fin');");
			}
		}
		setAvancement($i*100/$total);
	}
	
	$rq = "TRUNCATE TABLE `glpi`.`glpi_plugin_fusioninventory_ipranges`";
	$reponse = $DB->query($rq);
	
	foreach($tabRQ as $rq)
	{
		$DB->query($rq);
	}
	setUpdateDate();
}
?>