<?php
	if(empty($_SESSION['identifiant'])) 
	{
		exit;
	}

	require_once("bd.php");

	function obtenirTables($bd, $id){
        try{
			$sql = "SELECT * FROM tables";
			$paramsSql = array();
			
			if ($id != null){
				$sql = $sql . " WHERE id = :id";
				$paramsSql[':id'] = $id;
			}
			$requete = $bd->prepare($sql);
			$requete->execute($paramsSql);

			$resultat = array();

			while($ligne=$requete->fetch()){
				$resultat[] = $ligne;
			}
			
			return $resultat;
        }catch(PDOException $ex){
			die($ex->getMessage());
		}
	}
	
	function creerTable($bd, $nPlaces){
        try{
			$sql = "INSERT INTO tables (`nombrePlaces`) VALUES(:nPlaces);";
			$paramsSql = array(
				':nPlaces' => $nPlaces
			);

			$requete = $bd->prepare($sql);
			$requete->execute($paramsSql);
        }catch(PDOException $ex){
			die($ex->getMessage());
		}
	}
	
	function supprimerTable($bd, $id){
		try{
			$sql = "DELETE FROM `tables` WHERE `id` = :id;";
			$paramsSql = array(
				':id' => $id
			);
			
			$requete = $bd->prepare($sql);
			$requete->execute($paramsSql);
        }catch(PDOException $ex){
			die($ex->getMessage());
		}
	}
?>