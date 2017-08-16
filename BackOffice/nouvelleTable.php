<?php
	session_start();
	
	require_once("php/tables.php");

	if(!empty($_POST)){
        if(empty($_POST['nPlaces'])){
			die("Le nombre de places est requis");
		}

		$nPlaces = trim($_POST['nPlaces']);
		
        try{
			creerTable($connBd, $nPlaces);

			header("Location: tables.php");
        }catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
?>