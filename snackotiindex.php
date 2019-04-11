<?php
/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2015      Jean-François Ferry	<jfefe@aternatik.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file       htdocs/mymodule/template/mymoduleindex.php
 *	\ingroup    mymodule
 *	\brief      Home page of mymodule top menu
 */

// Load Dolibarr environment
$res=0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (! $res && ! empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res=@include($_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php");
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp=empty($_SERVER['SCRIPT_FILENAME'])?'':$_SERVER['SCRIPT_FILENAME'];$tmp2=realpath(__FILE__); $i=strlen($tmp)-1; $j=strlen($tmp2)-1;
while($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i]==$tmp2[$j]) { $i--; $j--; }
if (! $res && $i > 0 && file_exists(substr($tmp, 0, ($i+1))."/main.inc.php")) $res=@include(substr($tmp, 0, ($i+1))."/main.inc.php");
if (! $res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i+1)))."/main.inc.php")) $res=@include(dirname(substr($tmp, 0, ($i+1)))."/main.inc.php");
// Try main.inc.php using relative path
if (! $res && file_exists("../main.inc.php")) $res=@include("../main.inc.php");
if (! $res && file_exists("../../main.inc.php")) $res=@include("../../main.inc.php");
if (! $res && file_exists("../../../main.inc.php")) $res=@include("../../../main.inc.php");
if (! $res) die("Include of main fails");

require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/snackoti/class/snackoti.class.php';
// Load translation files required by the page
$langs->loadLangs(array("snackoti@snackoti"));


// Securite acces client
if (! $user->rights->snackoti->read) accessforbidden();
$socid=GETPOST('socid','int');
if (isset($user->societe_id) && $user->societe_id > 0)
{
	$action = '';
	$socid = $user->societe_id;
}


//  Page "Prise de commandes + historique de vente + calcules de la caisse 
 

//initialise object
$SnackOti = new SnackOti($db);
$allThirdContact = new Societe($db);

// Parameters
$p_action = GETPOST('action');
$p_client = GETPOST('client');
$p_pouletfrite = GETPOST('pouletfrite');
$p_steackfrite = GETPOST('steackfrite');
$p_chippofrite = GETPOST('chippofrite');
$p_chippo = GETPOST('chippo');
$p_brochette = GETPOST('brochette');
$p_frite = GETPOST('frite');
$p_boisson = GETPOST('boisson');
$p_typecommande = GETPOST('typecommande');
$p_rowidCommande = GETPOST('rowidCommande');
$p_jour = GETPOST('jour');
$p_fondcaisse = GETPOST('fondcaisse');
$p_retraitcaisse = GETPOST('retraitcaisse');
// $max=5;
// $now=dol_now();

$CountMenu = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
/*
 * Actions
 */
if($p_action == 'newvente')
{
	$SnackOti->newvente($p_jour);
	$p_jour = $SnackOti->getvente();
	$p_jour = count($p_jour);
	$SnackOti->createfondcaisse($p_fondcaisse,$p_jour);
}

if($p_action == 'update')
{
	$SnackOti->updatefieldpayer($p_rowidCommande);
}

if($p_action == 'creer')
{
	$SnackOti->createnewclient($p_client);
	$p_client = $listThirdContact = $allThirdContact->getAllThird();
	$p_client = count($p_client);
	$res = $SnackOti->create($p_jour,$p_client,$p_pouletfrite,$p_steackfrite,$p_chippofrite,$p_chippo,$p_brochette,$p_frite,$p_boisson,$p_typecommande);

	if ($res > 0) {
		$p_action = 'newvente';
		setEventMessage('Commande validé', 'mesgs');
	}else{
		setEventMessage('Commande non validé', 'error');
	}
}

if($p_action == 'consultation'){
	$getvente = $SnackOti->getvente();
	$getvente = count($getvente);
	if($getvente == $p_jour){
		$p_action = "newvente";
	}else{
		$p_action = "consultation";
	}
}

if($p_action == 'retraitcaisse'){
	$res = $SnackOti->retraitcaisse($p_retraitcaisse,$p_jour);
	
	if ($res > 0) {
		setEventMessage('Retrait validé', 'mesgs');
	}else{
		setEventMessage('Retrait non validé', 'error');
	}
}

$getpanier = $SnackOti->getpanier($p_jour);
$getventeid = $SnackOti->getventeId($p_jour);
$getsumcaisse = $SnackOti->sumcaisse($p_jour);
$getfondcaisse = $SnackOti->getfondcaisse($p_jour);
$sumpouletf = $SnackOti->sumpouletf($p_jour);
$sumsteackf = $SnackOti->sumsteackf($p_jour);
$sumchippof = $SnackOti->sumchippof($p_jour);
$sumchippo = $SnackOti->sumchippo($p_jour);
$sumbrochette = $SnackOti->sumbrochette($p_jour);
$sumfrite = $SnackOti->sumfrite($p_jour);
$sumboisson = $SnackOti->sumboisson($p_jour);
$get_retraitcaisse = $SnackOti->getretraitcaisse($p_jour);

//a la création de la vente, récupère le dernier jours enregistré en base de donnée
if(isset($p_jour)){
	$p_jour = $SnackOti->getvente();
	$p_jour = count($p_jour);
	$getventeid = $SnackOti->getventeId($p_jour);
}


/*
 * View
 */

$form = new Form($db);
$formfile = new FormFile($db);


// Start of transaction
$db->begin();

//*/Récupère les méthodes d'une autres classe
require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
$listThirdContact = $allThirdContact->getAllThird();


llxHeader("",$langs->trans("SnackOtiArea"));

// print load_fiche_titre($langs->trans("SnackOtiArea"),'','mymodule.png@snackoti');

// /**************************************   fiche center    ******************************/
// print '<div class="fichecenter">';
	
	if($p_action == 'consultation'){
		print '<div class="row">';
			print '<a class="btn btn-outline-info" href="index.php" role="button">Liste des ventes</a>';
		print '</div>';
		print '<br>';

		print '<div class="card shadow-sm p-1 mb-3 bg-white rounded">';
						print '<div class="card-body">';
							print '<div class="row">';
								//Statistique Caisse
								print'<div class="card " style="width: 12.5rem;">';
									print '<div class="card-header text-white bg-danger">';
										print 'Caisse';
									print '</div>';
									print '<ul class="list-group list-group-flush">';
										//fond de caisse
										print '<li class="list-group-item d-flex justify-content-between align-items-center">fond de caisse: ';
										foreach($getfondcaisse as $fondcaisse){
											$fond_caisse = $fondcaisse;
											print '<span class="badge badge-darck badge-pill">'.$fondcaisse['fondcaisse'].'</span>';
										}
										print '</li>';
										//gains de la vente
										print '<li class="list-group-item d-flex justify-content-between align-items-center">vente: ';
										foreach($getsumcaisse as $sumcaisse){
											$sum_caisse = $sumcaisse;
											print '<span class="badge badge-warning badge-pill">'.strstr($sumcaisse['totalcaisse'], '.', 1).'</span>';
										}
										print '</li>';
										// if ($p_action == 'retrait') {
										// 	print '<form action="'.$_SERVER['PHP_SELF'].'?action=retraitcaisse&jour='.$p_jour.'" method="POST">';
										// 	print '<input type="text" name="retraitcaisse" value="'.$p_retraitcaisse.'" placeholder="entrer un chiffre">';
										// 	print '<button type="submit" data-toggle="tooltip" data-placement="top" title="Retrait de caisse" class="btn btn-outline-danger">Valider</button>';
										// 	print '</form>';
										// }else{
											print '<li class="list-group-item d-flex justify-content-between align-items-center">retrait: ';
											print '<span class="badge badge-success badge-pill ">'.$get_retraitcaisse['retrait'].'</span>';
											print '</li>';
										// }
										//Total caisse
										//Addition
										$total_caisse = $fond_caisse['fondcaisse'] + $sum_caisse['totalcaisse'];
										//Soustraction
										$total_caisse = $total_caisse - $get_retraitcaisse['retrait'];
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Total: ';
										print '<span class="badge badge-danger badge-pill ">'.$total_caisse.'</span>';
										print '</li>';
									print '</ul>';
								print '</div>';
								print '<br>';

								//Statistique Menu
								print'<div class="card" style="width: 12.5rem;">';
									print '<div class="card-header text-white bg-primary">';
									print 'Menu';
									print '</div>';
									print '<ul class="list-group list-group-flush">';
										//statistique poulet
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Poulet Frite: ';
										foreach($sumpouletf as $poulet){
											print '<span class="badge badge-primary badge-pill">'.$poulet['qtt_pouletf'].'</span>';
										}
										print '</li>';
										//statistique steack
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Steack Frite: ';
										foreach($sumsteackf as $steack){
											print '<span class="badge badge-primary badge-pill">'.$steack['qtt_steackf'].'</span>';
										}
										print '</li>';
										//statistique chippoFrite
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Chippo Frite: ';
										foreach($sumchippof as $chippof){
											print '<span class="badge badge-primary badge-pill">'.$chippof['qtt_chippof'].'</span>';
										}
										print '</li>';
									print '</ul>';
								print '</div>';

								//Statistique accompagnement
								print'<div class="card" style="width: 12.5rem;">';
									print '<div class="card-header text-white bg-info">';
									print 'Accompagnement';
									print '</div>';
									print '<ul class="list-group list-group-flush">';
										//statistique Chippo
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Chippo: ';
										foreach($sumchippo as $chippo){
											print '<span class="badge badge-primary badge-pill">'.$chippo['qtt_chippo'].'</span>';
										}
										print '</li>';
										//statistique Brochette
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Brochette: ';
										foreach($sumbrochette as $brochette){
											print '<span class="badge badge-primary badge-pill">'.$brochette['qtt_brochette'].'</span>';
										}
										print '</li>';
										//statistique Frite
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Frite: ';
										foreach($sumfrite as $frite){
											print '<span class="badge badge-primary badge-pill">'.$frite['qtt_frite'].'</span>';
										}
										print '</li>';
										//statistique Boisson
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Boisson: ';
										foreach($sumboisson as $boisson){
											print '<span class="badge badge-primary badge-pill">'.$boisson['qtt_boisson'].'</span>';
										}
										print '</li>';
									print '</ul>';
								print '</div>';
							//row statistique
							print '</div>';	
						//card body statistique
						print '</div>';
					//card statistique
					print '</div>';

		// print '<div class="row">';
		// 	print'<div class="card " style="width: 12rem;">';
		// 		print '<div class="card-header text-white bg-danger">';
		// 			print 'Caisse';
		// 		print '</div>';

		// 		print '<ul class="list-group list-group-flush">';
		// 			//fond de caisse
		// 			print '<li class="list-group-item d-flex justify-content-between align-items-center">fond de caisse: ';
		// 			foreach($getfondcaisse as $fondcaisse){
		// 				print '<span class="badge badge-darck badge-pill">'.$fondcaisse['fondcaisse'].'</span>';
		// 			}
		// 			print '</li>';
		// 			//bénéfice total de la vente
		// 			print '<li class="list-group-item d-flex justify-content-between align-items-center">vente: ';
		// 			foreach($getsumcaisse as $sumcaisse){
		// 				print '<span class="badge badge-danger badge-pill">'.strstr($sumcaisse['totalcaisse'], '.', 1).'</span>';
		// 			}
		// 			print '</li>';
		// 			//case vide
		// 			print '<li class="list-group-item"></li>';
		// 		print '</ul>';
		// 	print '</div>';
		// 	print '<br>';

		// 	//Statistique Menu
		// 	print'<div class="card" style="width: 12rem;">';
		// 		print '<div class="card-header text-white bg-primary">';
		// 			print 'Menu';
		// 		print '</div>';
		// 		print '<ul class="list-group list-group-flush">';
		// 			//statistique poulet
		// 			print '<li class="list-group-item d-flex justify-content-between align-items-center">Poulet Frite: ';
		// 			foreach($sumpouletf as $poulet){
		// 				print '<span class="badge badge-primary badge-pill">'.$poulet['qtt_pouletf'].'</span>';
		// 			}
		// 			print '</li>';
		// 			//statistique steack
		// 			print '<li class="list-group-item d-flex justify-content-between align-items-center">Steack Frite: ';
		// 			foreach($sumsteackf as $steack){
		// 				print '<span class="badge badge-primary badge-pill">'.$steack['qtt_steackf'].'</span>';
		// 			}
		// 			print '</li>';
		// 			//statistique chippoFrite
		// 			print '<li class="list-group-item d-flex justify-content-between align-items-center">Chippo Frite: ';
		// 			foreach($sumchippof as $chippof){
		// 				print '<span class="badge badge-primary badge-pill">'.$chippof['qtt_chippof'].'</span>';
		// 			}
		// 			print '</li>';
		// 		print '</ul>';
		// 	print '</div>';

		// 	//Statistique accompagnement
		// 	print'<div class="card" style="width: 12rem;">';
		// 		print '<div class="card-header text-white bg-info">';
		// 			print 'Accompagnement';
		// 		print '</div>';
		// 		print '<ul class="list-group list-group-flush">';
		// 			//statistique Chippo
		// 			print '<li class="list-group-item d-flex justify-content-between align-items-center">Chippo: ';
		// 			foreach($sumchippo as $chippo){
		// 				print '<span class="badge badge-primary badge-pill">'.$chippo['qtt_chippo'].'</span>';
		// 			}
		// 			print '</li>';
		// 			//statistique Brochette
		// 			print '<li class="list-group-item d-flex justify-content-between align-items-center">Brochette: ';
		// 			foreach($sumbrochette as $brochette){
		// 				print '<span class="badge badge-primary badge-pill">'.$brochette['qtt_brochette'].'</span>';
		// 			}
		// 			print '</li>';
		// 			//statistique Frite
		// 			print '<li class="list-group-item d-flex justify-content-between align-items-center">Frite: ';
		// 			foreach($sumfrite as $frite){
		// 				print '<span class="badge badge-primary badge-pill">'.$frite['qtt_frite'].'</span>';
		// 			}
		// 			print '</li>';
		// 			//statistique Boisson
		// 			print '<li class="list-group-item d-flex justify-content-between align-items-center">Boisson: ';
		// 			foreach($sumboisson as $boisson){
		// 				print '<span class="badge badge-primary badge-pill">'.$boisson['qtt_boisson'].'</span>';
		// 			}
		// 			print '</li>';
		// 		print '</ul>';
		// 	print '</div>';
		// print '</div>';

		print '<br>';

		//col-list commande
		print '<div class="col">';
			//Affichage liste commande
			print '<table class="noborder" >';
				print '<thead>';
					print '<tr class="liste_titre">';
						print '<th align="center" >Nom</th><th  align="center">Label</th><th   align="center">Qtt</th><th   align="center">Prix</th>';
						print '<th   align="center">Payer</th><th  align="center">Type</th>';
					print '</tr>';
				print '</thead>';
				// print '</table>';
				// // print '<div style="overflow:scroll; min-height:560px; max-height:560px;">';
				// print '<table class="noborder" >';
				print '<tbody>';
				$parity = TRUE;
					foreach($getpanier as $panier){
						$parity = !$parity;
						print '<tr class="'.($parity?'pair':'impair').'">';
						print '<th >'.$panier['nom'].' '.$panier['rowid'].'</th>';
						print '<th >'.$panier['label'].'</th>';
						print '<th >'.$panier['qtt'].'</th>';

						$prix = $panier['qtt']*$panier['prix'];

						print '<th >'.$prix.'</th>';

							//Payer ou Impayer
							if($panier['type'] > 0){
								print '<th> -- </th>';
							}elseif(!($panier['payer'])){
								print '<th ><a href="snackotiindex.php?jour='.$p_jour.'&rowidCommande='.$panier['rowid'].'&action=update">Impayer</a></th>';
							}else{
								print '<th >Payer</th>';
							}

							//vendue ou famille
							if($panier['type'] == 0){
								print '<th >Vendu</th>';
							}else{
								print '<th >Famille</th>';
							}

						print '</tr>';
					}
				print '</tbody>';
			print '</table>';
		//col ListCommande
		print '</div>';

	}else{

		print '<div class="container-fluid">';
			print '<br><div class="row">';
				print '<div class="col-2">';
				print '<a class="btn btn-info text-white" href="index.php" role="button">Liste des ventes</a>';
				print '</div>';
				print '<div class="col-8 text-center">';
					//jour de la vente
					foreach($getventeid as $venteid)
					{
						print '<h3>Vente du '.$venteid['jour'].' '.$venteid['date'].'</h3>';
					}
				print '</div>';
				print '<div class="col-2"></div>';
			print '</div><br>';

			
			print '<div class="row">';
				print '<div class="col">';
					print '<div class="card shadow-sm p-3 mb-3 bg-white rounded">';
						print '<div class="card-body">';
							print '<form action="'.$_SERVER['PHP_SELF'].'?action=creer" method="POST">';
								print '<div class="form-row">';
									print '<input type="hidden" name="jour" value="'.$p_jour.'">';

									print '<div class="form-group">';
										print '<label>Nom du Client</label>';
										print '<input type="text" class="form-control" name="client" value="'.$p_client.'">';
										print '<small id="emailHelp" class="form-text text-muted">Si enfant, mettre le nom  des parents.</small>';
									print '</div>';

									// //Nb PouletFrite
									print '<div class="form-group col">';
											print '<label>PouletF</label>';
											print '<select id="select_pouletfrite" class="form-control" name="pouletfrite" value="'.$p_pouletfrite.'">';
												foreach($CountMenu as $pf)
												{
													print '<option value="'.$pf.'">'.$pf.'</option>';
												}
											print '</select>';
										print '</div>';

									// //Nb SteackFrite
									print '<div class="form-group col">';
											print '<label>SteackF</label>';
											print '<select id="select_steackfrite" class="form-control" name="steackfrite" value="'.$p_steackfrite.'">';
												foreach($CountMenu as $sf)
												{
													print '<option value="'.$sf.'">'.$sf.'</option>';
												}
											print '</select>';
										print '</div>';

									// //Nb ChippoFrite
									print '<div class="form-group col">';
											print '<label>ChippoF</label>';
											print '<select id="select_chippofrite" class="form-control" name="chippofrite" value="'.$p_chippofrite.'">';
												foreach($CountMenu as $cf)
												{
													print '<option value="'.$cf.'">'.$cf.'</option>';
												}
											print '</select>';
									print '</div>';

									//Nb Chippo
									print '<div class="form-group col">';
										print '<label>Chippo</label>';
										print '<select id="select_chippo" class="form-control" name="chippo" value="'.$p_chippo.'">';
												foreach($CountMenu as $c)
												{
													print '<option value="'.$c.'">'.$c.'</option>';
												}
										print '</select>';
									print '</div>';

									//Nb Brochette
									print '<div class="form-group col">';
										print '<label>Brochette</label>';
										print '<select id="select_brochette" class="form-control" name="brochette" value="'.$p_brochette.'">';
												foreach($CountMenu as $br)
												{
													print '<option value="'.$br.'">'.$br.'</option>';
												}
										print '</select>';
									print '</div>';
								//form row
								print '</div>';
								print '<div class="form-row">';
									//Nb Frite
									print '<div class="form-group col-md-3">';
										print '<label>Frite</label>';
										print '<select id="select_frite" class="form-control" name="frite" value="'.$p_frite.'">';
											foreach($CountMenu as $f)
											{
												print '<option value="'.$f.'">'.$f.'</option>';
											}
										print '</select>';
									print '</div>';

									//Nb Boisson
									print '<div class="form-group col-md-3">';
										print '<label>Boisson</label>';
										print '<select id="select_boisson" name="boisson" class="form-control" value="'.$p_boisson.'">';
											foreach($CountMenu as $b)
											{
												print '<option value="'.$b.'">'.$b.'</option>';
											}
										print '</select>';
									print '</div>';
								// //Type de commande 0=vente, 1=don
									print '<div class="form-group col-md-3">
											<label>Type</label>
											<select class="form-control" name="typecommande" value="'.$p_typecommande.'">
												<option value="0" selected>Vente</option>
												<option value="1">Famille</option>
											</select>';
									print '</div>';
									print '<div class="form-group col-md-2">';
									print '<label>Action</label>';
									print '<button type="submit" data-toggle="tooltip" data-placement="top" title="Créer la commande" class="btn btn-outline-success">Valider</button>';
									print '</div>';
								//form-row
								print '</div>';
							print '</form>';
						print '</div>';
					print '</div>';

					//*****************************************************Statistique*******************************************************************************/
					
					print '<div class="card shadow-sm p-1 mb-3 bg-white rounded">';
						print '<div class="card-body">';
							print '<div class="row">';
								//Statistique Caisse
								print'<div class="card " style="width: 12.5rem;">';
									print '<div class="card-header text-white bg-danger">';
										print 'Caisse';
									print '</div>';
									print '<ul class="list-group list-group-flush">';
										//fond de caisse
										print '<li class="list-group-item d-flex justify-content-between align-items-center">fond de caisse: ';
										foreach($getfondcaisse as $fondcaisse){
											$fond_caisse = $fondcaisse;
											print '<span class="badge badge-darck badge-pill">'.$fondcaisse['fondcaisse'].'</span>';
										}
										print '</li>';
										//gains de la vente
										print '<li class="list-group-item d-flex justify-content-between align-items-center">vente: ';
										foreach($getsumcaisse as $sumcaisse){
											$sum_caisse = $sumcaisse;
											print '<span class="badge badge-warning badge-pill">'.strstr($sumcaisse['totalcaisse'], '.', 1).'</span>';
										}
										print '</li>';
										if ($p_action == 'retrait') {
											print '<form action="'.$_SERVER['PHP_SELF'].'?action=retraitcaisse&jour='.$p_jour.'" method="POST">';
											print '<input type="text" name="retraitcaisse" value="'.$p_retraitcaisse.'" placeholder="entrer un chiffre">';
											print '<button type="submit" data-toggle="tooltip" data-placement="top" title="Retrait de caisse" class="btn btn-outline-danger">Valider</button>';
											print '</form>';
										}else{
											print '<li class="list-group-item d-flex justify-content-between align-items-center"><a href="snackotiindex.php?action=retrait&jour='.$p_jour.'">retrait: </a>';
											print '<span class="badge badge-success badge-pill ">'.$get_retraitcaisse['retrait'].'</span>';
											print '</li>';
										}
										//Total caisse
										//Addition
										$total_caisse = $fond_caisse['fondcaisse'] + $sum_caisse['totalcaisse'];
										//Soustraction
										$total_caisse = $total_caisse - $get_retraitcaisse['retrait'];
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Total: ';
										print '<span class="badge badge-danger badge-pill ">'.$total_caisse.'</span>';
										print '</li>';
									print '</ul>';
								print '</div>';
								print '<br>';

								//Statistique Menu
								print'<div class="card" style="width: 12.5rem;">';
									print '<div class="card-header text-white bg-primary">';
									print 'Menu';
									print '</div>';
									print '<ul class="list-group list-group-flush">';
										//statistique poulet
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Poulet Frite: ';
										foreach($sumpouletf as $poulet){
											print '<span class="badge badge-primary badge-pill">'.$poulet['qtt_pouletf'].'</span>';
										}
										print '</li>';
										//statistique steack
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Steack Frite: ';
										foreach($sumsteackf as $steack){
											print '<span class="badge badge-primary badge-pill">'.$steack['qtt_steackf'].'</span>';
										}
										print '</li>';
										//statistique chippoFrite
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Chippo Frite: ';
										foreach($sumchippof as $chippof){
											print '<span class="badge badge-primary badge-pill">'.$chippof['qtt_chippof'].'</span>';
										}
										print '</li>';
									print '</ul>';
								print '</div>';

								//Statistique accompagnement
								print'<div class="card" style="width: 12.5rem;">';
									print '<div class="card-header text-white bg-info">';
									print 'Accompagnement';
									print '</div>';
									print '<ul class="list-group list-group-flush">';
										//statistique Chippo
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Chippo: ';
										foreach($sumchippo as $chippo){
											print '<span class="badge badge-primary badge-pill">'.$chippo['qtt_chippo'].'</span>';
										}
										print '</li>';
										//statistique Brochette
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Brochette: ';
										foreach($sumbrochette as $brochette){
											print '<span class="badge badge-primary badge-pill">'.$brochette['qtt_brochette'].'</span>';
										}
										print '</li>';
										//statistique Frite
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Frite: ';
										foreach($sumfrite as $frite){
											print '<span class="badge badge-primary badge-pill">'.$frite['qtt_frite'].'</span>';
										}
										print '</li>';
										//statistique Boisson
										print '<li class="list-group-item d-flex justify-content-between align-items-center">Boisson: ';
										foreach($sumboisson as $boisson){
											print '<span class="badge badge-primary badge-pill">'.$boisson['qtt_boisson'].'</span>';
										}
										print '</li>';
									print '</ul>';
								print '</div>';
							//row statistique
							print '</div>';	
						//card body statistique
						print '</div>';
					//card statistique
					print '</div>';

				//col Formulaire
				print '</div>';
				
				//***************************************************** Liste commande *******************************************************************************/
					
				print '<div class="col"  >';
					//Affichage liste commande
					print '<div style="overflow:scroll; max-height:580px;">';
					print '<table class="table" >';
						print '<thead class="text-white bg-info">';
							print '<tr>';
								print '<th align="center" scope="col">Nom</th><th scope="col"  align="center">Label</th><th  scope="col" align="center">Qtt</th><th  scope="col" align="center">Prix</th>';
								print '<th scope="col"   align="center">Payer</th><th scope="col"  align="center">Type</th>';
							print '</tr>';
						print '</thead>';
						// print '</table>';
						
						// print '<table class="table">';
						print '<tbody>';
						foreach($getpanier as $panier){								
							print '<tr >';
								print '<th align="center" scope="col">'.$panier['nom'].' '.$panier['rowid'].'</th>';
								print '<th align="center" scope="col">'.$panier['label'].'</th>';
								print '<th align="center" scope="col">'.$panier['qtt'].'</th>';

								//multiplication prix*quantité
								$prix = $panier['qtt']*$panier['prix'];

								print '<th align="center" scope="col">'.$prix.'</th>';

								//Payer ou Impayer
								if($panier['type'] > 0){
									print '<th align="center" scope="col"> -- </th>';
								}elseif(!($panier['payer'])){
									print '<th align="center" scope="col"><a class="btn btn-outline-danger" href="snackotiindex.php?jour='.$p_jour.'&rowidCommande='.$panier['rowid'].'&action=update">Impayer</a></th>';
								}else{
									print '<th align="center" scope="col">Payer</th>';
								}

								//vendue ou famille
								if($panier['type'] == 0){
									print '<th align="center" scope="col">Vendu</th>';
								}else{
									print '<th align="center" scope="col">Famille</th>';
								}

							print '</tr>';
							}
						print '</tbody>';
					print '</table>';
				//col ListCommande
				print '</div>';
			//row
			print '</div>';
		//containerFluid
		print '</div>';

	}

	
llxFooter();

$db->close();
?>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
