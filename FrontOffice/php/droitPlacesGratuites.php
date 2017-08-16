<?php
	session_start();

	if(empty($_SESSION['identifiant'])) 
	{
		exit;
	}
	
	require_once("bd.php");

	$sql = "SELECT nombreTamponsCarteFidelite FROM clients WHERE id = :id;"; 
	$paramsSql = array(
		':id' => $_SESSION['id']
	);

	try{
		$requete = $connBd->prepare($sql);
		$requete->execute($paramsSql);

		$resultat = $requete->fetch();

		$nTampons = $resultat['nombreTamponsCarteFidelite'];
		$nPlacesGratuites = floor($nTampons /10);

		echo($nPlacesGratuites);
	}catch(PDOException $ex){
		echo($ex->getMessage());
	}
?>