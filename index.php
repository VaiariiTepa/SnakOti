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
require_once DOL_DOCUMENT_ROOT.'/snackoti/class/snackoti.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';

// Load translation files required by the page
$langs->loadLangs(array("snackoti@snackoti"));

$action=GETPOST('action');
$p_jour=GETPOST('jour');

// Securite acces client
if (! $user->rights->snackoti->read) accessforbidden();
$socid=GETPOST('socid','int');
if (isset($user->societe_id) && $user->societe_id > 0)
{
	$action = '';
	$socid = $user->societe_id;
}

$SnakOti = new SnackOti($db);

/**
 * Page "Créer une vente OU ouvrire une vente
 */

/*
 * View
 */

$form = new Form($db);
$formfile = new FormFile($db);

$getvente = $SnakOti->getvente();

llxHeader("","Snack OTI -- Liste des Ventes");

// print load_fiche_titre("Snack OTI -- Liste des Ventes",'','mymodule.png@mymodule');

print '<div id="listventeContainer" class="container">';
	print '<div class="row">';
		print '<div class="col-sm-2">';
		print '</div>';

		print '<div  class="col-sm-8">';
			print '<div class="card">';
				print '<div class="card-body">';
					//formulaire creation de vente
					print '<form action="snackotiindex.php?action=newvente" method="POST">';
						print '<div id="listventejour" class="form-row">';	
							print '<div class="form-group col">';
								print '<label for="inputState">Jour</label>';
								print '<select id="inputState" class="form-control" name="jour" value="'.$p_jour.'">';
									print '<option value="lundi">Lundi</option><option value="mardi">Mardi</option><option value="mercredi">Mercredi</option>';
									print '<option value="jeudi">Jeudi</option><option value="vendredi">Vendredi</option><option value="samedi">Samedi</option><option value="dimanche">Dimanche</option>';
								print '</select>';
							print '</div>';
							print '<div class="form-group col">';
								print '<label for="exampleInputEmail1">Caisse</label>';
								print '<input type="text" class="form-control" name="fondcaisse" value="'.$p_fondcaisse.'" placeholder="fond de caisse" required>';
								print '<small id="emailHelp" class="form-text text-muted">Définir le fond de caisse</small>';
							print '</div>';
							print '<div class="form-group col">';
								print '<label for="inputState">Action</label>';
								print '<br>';
								print '<button type="submit" name="action" class="btn btn-primary" value="search_productphone">Nouvelle vente</button>';
							print '</div>';
						print '</div>';
					print '</form>';
				print '</div>';
			print '</div>';
			print '<br>';
			print '<div class="card">';
				print '<div class="card-body">';
					//Liste des ventes
					print '<div id="listvente">';
						print '<ul class="list-group">';
							print '<h5 class="list-group-item active text-center">Liste des ventes</h5>';
							print '<div style="overflow:scroll; max-height: 380px;">';
							foreach($getvente as $vente){
									print '<li class="list-group-item"><a href="snackotiindex.php?action=consultation&jour='.$vente['id'].'">'.$vente['jour'].' -- '.$vente['date'].'</a></li>';
							}
							print '</div>';
						print '</ul>';
					print '</div>';
				//card body liste vente
				print '</div>';
			//card liste vente
			print '</div>';

		//col-sm-8
		print '</div>';

		print '<div class="col-sm-2">';
		print '</div>';
	
	//row
	print '</div>';

//Container
print '</div>';

llxFooter();

$db->close();
