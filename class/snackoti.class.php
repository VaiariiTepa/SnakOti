<?php
/* Copyright (C) 2017  Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
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
 * \file        htdocs/modulebuilder/template/class/myobject.class.php
 * \ingroup     mymodule
 * \brief       This file is a CRUD class file for MyObject (Create/Read/Update/Delete)
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';
require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

/**
 * La Classe que j'ai développer
 */

/**
 * Class for MyObject
 */
class SnackOti extends CommonObject
{

	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct(DoliDB $db)
	{
		global $conf, $user;

		$this->db = $db;

	}

	/**
	 * Créer un nouveaux client
	 */
	public function createnewclient($p_client)
	{
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."societe";
		$sql.= " (`nom`) VALUES";
		$sql.= " ('".$p_client."')";
		
		$this->db->query($sql);
	}

	/**
	 * Create Commande into llx_oti_panier
	 */
	/**
	 * @param $p_jour
	 * @param $p_client
	 * @param $p_pouletfrite
	 * @param $p_steackfrite
	 * @param $p_chippofrite
	 * @param $p_chippo
	 * @param $p_frite
	 * @param $p_boisson
	 * @param $p_typecommande
	 */
	public function create($p_jour,$p_client,$p_pouletfrite,$p_steackfrite,$p_chippofrite,$p_chippo,$p_brochette,$p_frite,$p_boisson,$p_typecommande)
	{
		$resulta = array();
		//si commande == vente, alors payer = 0, sinon payer = NULL
		if($p_typecommande == 0){
			$default_payer = 0;
		}else{
			$default_payer = 'NULL';
		}


		if($p_pouletfrite > 0)
		{
			$sql = "INSERT INTO ".MAIN_DB_PREFIX."oti_panier ";
			$sql.= " (`payer`,`fk_societe_id`,`fk_product_id`,`qtt`,`type`,`fk_oti_vente_id`) VALUES";
			$sql.= " (".$default_payer.','.$p_client.",1,".$p_pouletfrite.','.$p_typecommande.','.$p_jour.")";
			$resulta[] = $this->db->query($sql);
		}
		if($p_steackfrite > 0)
		{
			$sql = "INSERT INTO ".MAIN_DB_PREFIX."oti_panier ";
			$sql.= " (`payer`,`fk_societe_id`,`fk_product_id`,`qtt`,`type`,`fk_oti_vente_id`) VALUES";
			$sql.= " (".$default_payer.','.$p_client.",2,".$p_steackfrite.','.$p_typecommande.','.$p_jour.")";
			$resulta[] = $this->db->query($sql);
		}
		if($p_chippofrite > 0)
		{
			$sql = "INSERT INTO ".MAIN_DB_PREFIX."oti_panier ";
			$sql.= " (`payer`,`fk_societe_id`,`fk_product_id`,`qtt`,`type`,`fk_oti_vente_id`) VALUES";
			$sql.= " (".$default_payer.','.$p_client.",3,".$p_chippofrite.','.$p_typecommande.','.$p_jour.")";
			$resulta[] = $this->db->query($sql);
		}
		if($p_chippo > 0)
		{
			$sql = "INSERT INTO ".MAIN_DB_PREFIX."oti_panier ";
			$sql.= " (`payer`,`fk_societe_id`,`fk_product_id`,`qtt`,`type`,`fk_oti_vente_id`) VALUES";
			$sql.= " (".$default_payer.','.$p_client.",4,".$p_chippo.','.$p_typecommande.','.$p_jour.")";
			$resulta[] = $this->db->query($sql);
		}
		if($p_brochette > 0)
		{
			$sql = "INSERT INTO ".MAIN_DB_PREFIX."oti_panier ";
			$sql.= " (`payer`,`fk_societe_id`,`fk_product_id`,`qtt`,`type`,`fk_oti_vente_id`) VALUES";
			$sql.= " (".$default_payer.','.$p_client.",7,".$p_brochette.','.$p_typecommande.','.$p_jour.")";
			$resulta[] = $this->db->query($sql);
		}
		if($p_frite > 0)
		{
			$sql = "INSERT INTO ".MAIN_DB_PREFIX."oti_panier ";
			$sql.= " (`payer`,`fk_societe_id`,`fk_product_id`,`qtt`,`type`,`fk_oti_vente_id`) VALUES";
			$sql.= " (".$default_payer.','.$p_client.",5,".$p_frite.','.$p_typecommande.','.$p_jour.")";
			$resulta[] = $this->db->query($sql);
		}
		if($p_boisson > 0)
		{
			$sql = "INSERT INTO ".MAIN_DB_PREFIX."oti_panier ";
			$sql.= " (`payer`,`fk_societe_id`,`fk_product_id`,`qtt`,`type`,`fk_oti_vente_id`) VALUES";
			$sql.= " (".$default_payer.','.$p_client.",6,".$p_boisson.','.$p_typecommande.','.$p_jour.")";
			$resulta[] = $this->db->query($sql);
		}
		
		foreach($resulta as $result){
			if ($result == true) {
				$res = 1;
				return $res;
			}else{
				$res = 0;
				return $res;
			}
		}
	}

	/**
	 * Récupère les éléments du tableaux llx_oti_panier
	 */
	public function getpanier($p_jour)
	{
		$result = array();
		$sql = "select s.nom as nom
		,ov.jour as jour
		,ov.date as date
		,p.label as label
		,op.qtt as qtt
		,p.price as prix
		,op.payer as payer
		,op.livrer as livrer
		,op.type as type
		,op.id as rowid";
		$sql.= " from ".MAIN_DB_PREFIX."oti_panier as op"; 
		$sql.= " left join ".MAIN_DB_PREFIX."societe as s on op.fk_societe_id=s.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."product as p on op.fk_product_id=p.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."oti_vente as ov on op.fk_oti_vente_id=ov.id";
		$sql.= " WHERE op.fk_oti_vente_id=".$p_jour;

		$resql = $this->db->query($sql);

		if($resql){
			foreach ($resql as $resultat) {
				$result[] = $resultat;
			}
		}
		
        return $result;
	}

	/**
	 * calcul le bénéfice de la vente
	 */
	public function sumcaisse($p_jour)
	{
		$result = array();
		$sql = "select sum(p.price*op.qtt) as totalcaisse";
		$sql.= " from ".MAIN_DB_PREFIX."oti_panier as op"; 
		$sql.= " left join ".MAIN_DB_PREFIX."societe as s on op.fk_societe_id=s.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."product as p on op.fk_product_id=p.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."oti_vente as ov on op.fk_oti_vente_id=ov.id";
		$sql.= " WHERE op.fk_oti_vente_id=".$p_jour;
		$sql.= " AND op.type=0";
		$sql.= " AND op.payer=1";
		$resql = $this->db->query($sql);

		if($resql){
			foreach ($resql as $resultat) {
				$result[] = $resultat;
			}
		}
		
        return $result;
	}

	/**
	 * calcul le nombre de poulet vendue
	 */
	public function sumpouletf($p_jour)
	{
		$result = array();
		$sql = "select sum(op.qtt) as qtt_pouletf";
		$sql.= " from ".MAIN_DB_PREFIX."oti_panier as op"; 
		$sql.= " left join ".MAIN_DB_PREFIX."societe as s on op.fk_societe_id=s.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."product as p on op.fk_product_id=p.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."oti_vente as ov on op.fk_oti_vente_id=ov.id";
		$sql.= " WHERE op.fk_oti_vente_id=".$p_jour;
		$sql.= " AND op.type=0";
		$sql.= " AND op.payer=1";
		$sql.= " AND p.label='Poulet Frite'";

		$resql = $this->db->query($sql);

		if($resql){
			foreach ($resql as $resultat) {
				$result[] = $resultat;
			}
		}
        return $result;
	}

	/**
	 * calcul le nombre de steack vendue
	 */
	public function sumsteackf($p_jour)
	{
		$result = array();
		$sql = "select sum(op.qtt) as qtt_steackf";
		$sql.= " from ".MAIN_DB_PREFIX."oti_panier as op"; 
		$sql.= " left join ".MAIN_DB_PREFIX."societe as s on op.fk_societe_id=s.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."product as p on op.fk_product_id=p.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."oti_vente as ov on op.fk_oti_vente_id=ov.id";
		$sql.= " WHERE op.fk_oti_vente_id=".$p_jour;
		$sql.= " AND op.type=0";
		$sql.= " AND op.payer=1";
		$sql.= " AND p.label='Steack Frite'";

		$resql = $this->db->query($sql);

		if($resql){
			foreach ($resql as $resultat) {
				$result[] = $resultat;
			}
		}
        return $result;
	}

	/**
	 * calcul le nombre de chippo frite vendue
	 */
	public function sumchippof($p_jour)
	{
		$result = array();
		$sql = "select sum(op.qtt) as qtt_chippof";
		$sql.= " from ".MAIN_DB_PREFIX."oti_panier as op"; 
		$sql.= " left join ".MAIN_DB_PREFIX."societe as s on op.fk_societe_id=s.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."product as p on op.fk_product_id=p.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."oti_vente as ov on op.fk_oti_vente_id=ov.id";
		$sql.= " WHERE op.fk_oti_vente_id=".$p_jour;
		$sql.= " AND op.type=0";
		$sql.= " AND op.payer=1";
		$sql.= " AND p.label='Chippo Frites'";

		$resql = $this->db->query($sql);

		if($resql){
			foreach ($resql as $resultat) {
				$result[] = $resultat;
			}
		}
		
        return $result;
	}

	/**
	 * calcul le nombre de chippo vendue
	 */
	public function sumchippo($p_jour)
	{
		$result = array();
		$sql = "select sum(op.qtt) as qtt_chippo";
		$sql.= " from ".MAIN_DB_PREFIX."oti_panier as op"; 
		$sql.= " left join ".MAIN_DB_PREFIX."societe as s on op.fk_societe_id=s.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."product as p on op.fk_product_id=p.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."oti_vente as ov on op.fk_oti_vente_id=ov.id";
		$sql.= " WHERE op.fk_oti_vente_id=".$p_jour;
		$sql.= " AND op.type=0";
		$sql.= " AND op.payer=1";
		$sql.= " AND p.label='Chippo'";

		$resql = $this->db->query($sql);

		if($resql){
			foreach ($resql as $resultat) {
				$result[] = $resultat;
			}
		}
        return $result;
	}

	/**
	 * calcul le nombre de brochette vendue
	 */
	public function sumbrochette($p_jour)
	{
		$result = array();
		$sql = "select sum(op.qtt) as qtt_brochette";
		$sql.= " from ".MAIN_DB_PREFIX."oti_panier as op"; 
		$sql.= " left join ".MAIN_DB_PREFIX."societe as s on op.fk_societe_id=s.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."product as p on op.fk_product_id=p.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."oti_vente as ov on op.fk_oti_vente_id=ov.id";
		$sql.= " WHERE op.fk_oti_vente_id=".$p_jour;
		$sql.= " AND op.type=0";
		$sql.= " AND op.payer=1";
		$sql.= " AND p.label='Brochette'";
		
		$resql = $this->db->query($sql);

		if($resql){
			foreach ($resql as $resultat) {
				$result[] = $resultat;
			}
		}

        return $result;
	}

	/**
	 * calcul le nombre de frite vendue
	 */
	public function sumfrite($p_jour)
	{
		$result = array();
		$sql = "select sum(op.qtt) as qtt_frite";
		$sql.= " from ".MAIN_DB_PREFIX."oti_panier as op"; 
		$sql.= " left join ".MAIN_DB_PREFIX."societe as s on op.fk_societe_id=s.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."product as p on op.fk_product_id=p.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."oti_vente as ov on op.fk_oti_vente_id=ov.id";
		$sql.= " WHERE op.fk_oti_vente_id=".$p_jour;
		$sql.= " AND op.type=0";
		$sql.= " AND op.payer=1";
		$sql.= " AND p.label='Frites'";
		
		$resql = $this->db->query($sql);

		if($resql){
			foreach ($resql as $resultat) {
				$result[] = $resultat;
			}
		}

        return $result;
	}

	/**
	 * calcul le nombre de Boisson vendue
	 */
	public function sumboisson($p_jour)
	{
		$result = array();
		$sql = "select sum(op.qtt) as qtt_boisson";
		$sql.= " from ".MAIN_DB_PREFIX."oti_panier as op"; 
		$sql.= " left join ".MAIN_DB_PREFIX."societe as s on op.fk_societe_id=s.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."product as p on op.fk_product_id=p.rowid";
		$sql.= " left join ".MAIN_DB_PREFIX."oti_vente as ov on op.fk_oti_vente_id=ov.id";
		$sql.= " WHERE op.fk_oti_vente_id=".$p_jour;
		$sql.= " AND op.type=0";
		$sql.= " AND op.payer=1";
		$sql.= " AND p.label='Boisson'";
		
		$resql = $this->db->query($sql);

		if($resql){
			foreach ($resql as $resultat) {
				$result[] = $resultat;
			}
		}

        return $result;
	}


	/**
	 * Insère une nouvelle vente
	 * @param $p_jour
	 */
	public function newvente($p_jour)
	{
		$sql = " INSERT INTO ".MAIN_DB_PREFIX."oti_vente";
		$sql.= " (`jour`) VALUES";
		$sql.= " ('".$p_jour."')";
		$this->db->query($sql);
	}

	/**
	 * Creer fond de caisse
	 * @param $p_fondcaisse
	 * @param $p_jour
	 */
	public function createfondcaisse($p_fondcaisse,$p_jour)
	{
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."oti_caisse";
		$sql.= " (`fondcaisse`,`fk_oti_vente_id`) VALUES";
		$sql.= " (";
		$sql.= "$p_fondcaisse,";
		$sql.= "$p_jour";
		$sql.= ")";
		$this->db->query($sql);
	}

	/**
	 * récupère le fond de caisse
	 * @ int
	 */
	public function getfondcaisse($p_jour)
	{
		$result = array();
		//A FAIRE 
		//faire jointure pour récupérer le fond de caisse en fonction de l'id de la vente
		$sql = "SELECT * FROM ".MAIN_DB_PREFIX."oti_caisse";
		$sql.= " WHERE fk_oti_vente_id=".$p_jour;
		
		$resql = $this->db->query($sql);

		foreach($resql as $res){
			$result[] = $res;
		}
		return $result;
	}

	/**
	 * Récupère liste ventes
	 */
	public function getvente()
	{
		$result = array();

		$sql = "SELECT * FROM ".MAIN_DB_PREFIX."oti_vente";
		$resql = $this->db->query($sql);

		foreach($resql as $res){
			$result[] = $res;
		}
		return $result;
	}

	/**
	 * Récupère le jour de vente
	 */
	public function getventeId($p_id)
	{
		$result = array();

		$sql = "SELECT * FROM ".MAIN_DB_PREFIX."oti_vente";
		$sql.= " WHERE id=".$p_id;
		$resql = $this->db->query($sql);

		if($resql){
			foreach($resql as $res){
				$result[] = $res;
			}
		}

		return $result;
	}

	/**
	 * Mes a jour le status 'Impayer' a 'Payer'
	 */
	/**
	 * @param $p_rowidCommande
	 */
	public function updatefieldpayer($p_rowidCommande)
	{
		$sql = "UPDATE ".MAIN_DB_PREFIX."oti_panier";
				$sql.= " SET `payer`=1 WHERE id=".$p_rowidCommande;
				
				$this->db->query($sql);
	}

	/**
	 * enregistre un retrait de caisse
	 * @param $p_retraitcaisse
	 * @param $p_jour
	 */
	public function retraitcaisse($p_retraitcaisse,$p_jour)
	{

		$sql = "UPDATE `".MAIN_DB_PREFIX."oti_caisse` ";
		$sql.= " SET `retrait`=".$p_retraitcaisse;
		$sql.= " WHERE fk_oti_vente_id=".$p_jour;
		$resql = $this->db->query($sql);

		if($resql){
			$result = 1;
		}else{
			$result = 0;
		}

        return $result;
	}

	/**
	 * récupère les retrait de caisse du jour selectionner
	 */
	public function getretraitcaisse($p_jour)
	{
		$result = array();

		$sql = "SELECT `retrait` FROM ".MAIN_DB_PREFIX."oti_caisse";
		$sql.= " WHERE fk_oti_vente_id=".$p_jour;
		$resql = $this->db->query($sql);

		if($resql){
			foreach($resql as $res){
				$result[] = $res;
			}
			$result = reset($result);
		}

		return $result;
	}

}

/**
 * Class MyObjectLine. You can also remove this and generate a CRUD class for lines objects.
 */
/*
class MyObjectLine
{
	// @var int ID
	public $id;
	// @var mixed Sample line property 1
	public $prop1;
	// @var mixed Sample line property 2
	public $prop2;
}
*/
