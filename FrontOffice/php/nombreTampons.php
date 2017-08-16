<?php
	if(empty($_SESSION['identifiant'])) 
	{
		exit;
	}
	
	require_once("bd.php");
	
	function obtenirNombreTampons($bd){
		$sql = "SELECT nombreTamponsCarteFidelite FROM clients WHERE id = :id;"; 
		$paramsSql = array(
			':id' => $_SESSION['id']
		);

        try{
			$requete = $bd->prepare($sql);
			$requete->execute($paramsSql);
			
			$resultat = $requete->fetch();

			return $resultat['nombreTamponsCarteFidelite'];
        }catch(PDOException $ex){
			return 0;
		}
	}
?>