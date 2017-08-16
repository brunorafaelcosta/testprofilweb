<?php
	session_start();
	
	require_once("php/tables.php");

	if(!empty($_GET)){
        if(empty($_GET['id'])){
			die("L'id est requis");
		}

		$id = trim($_GET['id']);
		
        try{
			supprimerTable($connBd, $id);
			
			header("Location: tables.php");
        }catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
?>