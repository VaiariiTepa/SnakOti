-- Copyright (C) ---Put here your own copyright and developer email---
--
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see http://www.gnu.org/licenses/.


--
-- Structure de la table `llx_oti_panier`
--

CREATE TABLE IF NOT EXISTS `llx_oti_panier` (
  `id` int(11) NOT NULL auto_increment,
  `fk_societe_id` int(11) NOT NULL,
  `fk_product_id` int(11) NOT NULL,
  `payer` tinyint(1) NOT NULL,
  `livrer` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `qtt` int(11) NOT NULL,
  `fk_oti_vente_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


--
-- Structure de la table `llx_oti_vente`
--

CREATE TABLE IF NOT EXISTS `llx_oti_vente` (
  `id` int(11) NOT NULL auto_increment,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `jour` varchar(40) null,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


--
-- Structure de la table `llx_oti_caisse`
--

CREATE TABLE IF NOT EXISTS `llx_oti_caisse` (
  `id` int(11) NOT NULL auto_increment,
  `fondcaisse` int(11) NOT NULL,
  `fk_oti_vente_id` int(11) NOT NULL,
  `retrait` int(11) NULL,
  `description` varchar(255) NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
