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
	$tabSubnet = array();
	//on ignore la premiere ligne
	for($i=1; $i<=$total; $i++)
	{
		$tab = explode(":", $tabFichier["$i"]);
		$nomVlan = substr($tab[0],2);
		$strNetwork = substr($tab[1],1);
		$strNetwork = substr($strNetwork,0,-1);
		$tabNetwork = explode(" ", $strNetwork);
		
		// on recupere la liste des subnets deja présents (avant synchronisation) 
		$sql =  'SELECT name from glpi_plugin_fusioninventory_ipranges;';
		foreach  ($DB->query($sql) as $row) {
		array_push($tabSubnet,$row['name']);	
		}
		// on met l'attribut boolean a 0 (faux) pour tous les subnets
		$set0 = 'UPDATE `glpi`.`glpi_plugin_fusioninventory_ipranges` SET `bool`= 0;';
		$DB->query($set0);
		foreach($tabNetwork as $network)
		{
			$data = getArrayEntity($network);
			foreach($data as $subnet)
			{
				$entity = $subnet['entity'];
				list($debut, $fin) = get_IP_range($network);
				$nomReseau = $subnet['name'].'-'.$nomVlan.'@'.$network;
				// si le subnet est deja présent, on met l'attribut boolean a 1 (vrai) 
				if(in_array($nomReseau,$tabSubnet)){
					array_push($tabRQ, "UPDATE `glpi`.`glpi_plugin_fusioninventory_ipranges` SET `bool`= 1 WHERE `name`='$nomReseau';");

				}
				//si c'est un nouveau subnet, on fait un insert avec comme id la valeur auto incrementé et on met le boolean a 1 (vrai)
				else{
					
					array_push($tabRQ, "INSERT INTO `glpi`.`glpi_plugin_fusioninventory_ipranges` (`name`, `entities_id`, `ip_start`, `ip_end`, `bool`) VALUES ('$nomReseau', '$entity', '$debut', '$fin', 1);");
                    			}
			}
		}
		setAvancement($i*100/$total);
	}
	
	foreach($tabRQ as $rq)
	{
		$DB->query($rq);
	}
	
	//on supprime les subnets dont le boolean est 0 (c-a-d les subnets ayant été supprimé)
	$del0 = "DELETE FROM `glpi`.`glpi_plugin_fusioninventory_ipranges` WHERE bool=0";
      	$reponse = $DB->query($del0);

	setUpdateDate();
}
?>
