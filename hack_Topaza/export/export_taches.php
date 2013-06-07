<?php
////	INIT
require "../../module_tache/commun.inc.php";
droit_acces_controler($objet["tache_dossier"], $_REQUEST["id_dossier"], 1);
require "export_taches.inc.php";


////	EXPORTE LES TACHES
////
if(isset($_POST["export_format"]))
{
	////	LISTE DES CONTACTS
	$contenu_export = "";
	$liste_contacts = db_tableau("SELECT titre,description,priorite,avancement,charge_jour_homme`,budget_disponible,budget_engage,devise,date_debut,date_fin FROM gt_tache WHERE id_dossier='".intval($_REQUEST["id_dossier"])."' ".sql_affichage($objet["tache"],$_REQUEST["id_dossier"]));
	//SELECT nom, prenom FROM `gt_utilisateur` WHERE gt_utilisateur.id_utilisateur=(SELECT id_utilisateur FROM gt_tache_responsable WHERE id_tache=1)
	////	EXPORT CSV

		// INIT
		$tab_csv = $formats_csv[$_POST["export_format"]];
		$nom_fichier = $_POST["export_format"].".csv";
		// ENTETE DU FICHIER CSV
		foreach($tab_csv["champs"] as $champ_agora => $champ_csv)	{ $contenu_export .= $tab_csv["delimiteur"].$champ_csv.$tab_csv["delimiteur"].$tab_csv["separateur"]; }
		$contenu_export .= "\n";
		// AJOUT DE CHAQUE CONTACT (exporte les champs de chaque contacts)
		foreach($liste_contacts as $contact)
		{
			foreach($tab_csv["champs"] as $champ_agora => $champ_csv)
			{
				if($tab_csv["delimiteur"]=="'")		$contact[$champ_agora] = addslashes($contact[$champ_agora]);
				if(isset($contact[$champ_agora]) && $contact[$champ_agora]!="")		$contenu_export .= $tab_csv["delimiteur"].$contact[$champ_agora].$tab_csv["delimiteur"].$tab_csv["separateur"];
				else																$contenu_export .= $tab_csv["separateur"];
			}
			$contenu_export .= "\n";
		}

	/////   LANCEMENT DU TELECHARGEMENT
	telecharger($nom_fichier, false, $contenu_export);
}


////	HEADER & TITRE DU POPUP
////
require_once "../../include/header.inc.php";

?>


<script type="text/javascript"> resize_iframe_popup(500,250); </script>
<style type="text/css">
body { background-image:url('<?php echo "../../templates/"; ?>module_utilisateurs/fond_popup.png'); font-weight:bold; }
</style>


<form action="<?php echo php_self(); ?>" method="post" style="text-align:center;margin-top:10px;">
	<?php echo $trad["export_format"]; ?>
	<select name="export_format">
		<?php
		foreach($formats_csv as $format_csv=>$infos_csv)	{ echo "<option value='".$format_csv."'>".strtoupper($format_csv)."</option>"; }
		?>
		<option value='ldif'>LDIF</option>
	</select> &nbsp; 
	<input type="submit" value="<?php echo $trad["valider"]; ?>" class="button" />
	<input type="hidden" name="id_dossier" value="<?php echo @$_REQUEST["id_dossier"]; ?>" />
</form>


<?php require "../../include/footer.inc.php"; ?>