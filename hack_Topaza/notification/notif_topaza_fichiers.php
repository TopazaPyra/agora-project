<?php
//// MODULE FICHIER

	if(isset($_POST["notification"]))
	{
		$liste_id_destinataires = users_affectes($objet["fichier"], $_POST["id_fichier"]);
		$nomdossier = db_ligne("SELECT nom FROM gt_fichier_dossier WHERE id_dossier=".$_POST["id_dossier"].";");
		if($nomdossier['nom']=="") { $nomdossier['nom']="Racine";}
		$objet_mail = "[".$_SESSION["espace"]["nom"]."] : Fichier - Dossier ".$nomdossier['nom'];
		$contenu_mail = "Nom du fichier : ".$_POST["nom"].$_POST["extension"];
		
		$_FILES[0]["error"] = 0;
        	$_FILES[0]["tmp_name"] = PATH_MOD_FICHIER . chemin($objet["fichier_dossier"], $fichier_tmp["id_dossier"], "url") . $fichier_derniere_version["nom_reel"];
        	$_FILES[0]["name"] = $fichier_tmp["nom"];
		
		$options = array("notif"=>true);
        	if(empty($_POST["notif_joindre_fichiers"]))		$options["fichiers_joints"] = false;
		
		$lien = $_SESSION["agora"]["adresse_web"];
		if(strpos($lien, "index.php")){
			$lien = rtrim($lien, "index.php");
		}
		$lien .= "/".MODULE_PATH."/index.php?id_dossier=".$_POST["id_dossier"];
		
		if($_POST["description"]!="")	{ $contenu_mail .= "<br /><br /> Description du fichier : ".$_POST["description"]."<br />";		}
		$contenu_mail .= "<br /> Lien du dossier : ".$lien;
		envoi_mail($liste_id_destinataires, $objet_mail, magicquotes_strip($contenu_mail), $options);
	}

?>
