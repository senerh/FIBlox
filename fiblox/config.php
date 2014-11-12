<?php
// Entry menu case


include(__DIR__."/../../inc/includes.php");
include(__DIR__."/front/fonctions.php");

Session::checkRight("config", "w");

// To be available when plugin in not activated
Plugin::load('fiblox');

if (isset($_POST['ip']) && isset($_POST['user']) && isset($_POST['password']) && !empty($_POST['ip']) && !empty($_POST['user']) && !empty($_POST['password']))//Traitement
{
        //Récupération des données
        $ip = htmlspecialchars($_POST['ip']);
        $user = htmlspecialchars($_POST['user']);
        $password = htmlspecialchars($_POST['password']);

        $DB->query("UPDATE `glpi`.`glpi_plugin_fiblox_configuration` SET `ip` = '$ip', `user` = '$user', `password` = '$password' WHERE `glpi_plugin_fiblox_configuration`.`id` = 1;");
	header('Location: ' .basename($_SERVER['PHP_SELF']));



		
}
else//Saisie
{
	// $tab0 = configuration dans la BD avant l'éventuelle modification de celle-ci par la fonction isConfigured(); 
	$tab0 = getConfiguration();
        Html::header('FIBlox', $_SERVER['PHP_SELF'],"plugins","fiblox","config");
        if (isConfigured())
        {
                $msg = "Configuration correcte";
        }
        else
        {
                $msg = "Erreur de configuration, veuillez configurer correctement la connexion au serveur Infoblox.";
        }
	// $tab = configuration apres éventuelle modification par isConfigured();
        $tab = getConfiguration();

        $len = strlen($tab[2]);
        for ($i=0; $i<$len; $i++)
        {
                $tab[2][$i] = '*';
        }
        echo '
                <table class="tab_cadre_fixe">
                        <tbody>';
				if ($tab0[0]!=$tab[0])//si l'@ du serveur a été modifié par isConfigured() (passif->actif) 
				{
					$msg = "Configuration correcte";
					echo '<tr><th colspan="2"> <FONT color="red"> Le serveur '.$tab0[0].' (passif) a été changé en '.$tab[0].' (actif).</FONT> </th></tr>';
				}
				
               echo '           <tr><th colspan="2">'.$msg.'</th></tr>
                                <tr><td style="text-align: right;" width="50%">Adresse du serveur (FQDN)</td> <td style="text-align: left;" >'.$tab[0].'</td></tr>
                                <tr><td style="text-align: right;" >Utilisateur</td> <td style="text-align: left;" >'.$tab[1].'</td></tr>
                                <tr><td style="text-align: right;" >Mot de passe</td> <td style="text-align: left;" >'.$tab[2].'</td></tr>
                        </tbody>
                </table>';
#       echo basename($_SERVER['PHP_SELF']); = config.php               
        echo '
                <form action="'.basename($_SERVER['PHP_SELF']).'" method="post">
                        <table class="tab_cadre_fixe">
                                <tbody>
                                        <tr><th colspan="2">Connexion au serveur Infoblox</th></tr>
                                        <tr><td style="text-align: right;" width="50%">Adresse du serveur (FQDN)</td> <td style="text-align: left;" ><input type="text" name="ip"/></td></tr>
                                        <tr><td style="text-align: right;" >Utilisateur</td> <td style="text-align: left;" ><input type="text" name="user"/></td></tr>
                                        <tr><td style="text-align: right;" >Mot de passe</td> <td style="text-align: left;" ><input type="password" name="password"/></td></tr>
                                        <tr><td style="text-align: center;" colspan="2"><input class="submit" type="submit" value="Valider" /></td></tr>
                                </tbody>
                        </table>';
        Html::closeForm();
        Html::footer();
}
?>
