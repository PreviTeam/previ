<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	//Fake Datas --------------------------------------
	$nums=array("412", "413", "414");
	$nums2=array("512", "513", "514");

	$modele=array("Urbanway", $nums);
	$modele2=array("Mecos", $nums2);	

	$org=array("Bus", array($modele, $modele2));
	
	$tree=array($org);

	//end of Fake Datas --------------------------------------

	create_treeview("Arborescence Equipement", $tree);
	

	ob_end_flush();
?>