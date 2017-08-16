<?php
	$bd_host = 'localhost';
	$bd_user = 'webmaster';
	$bd_pass = '!;f6e5d4c3b2a1;!';
	$bd_name = 'profilweb_brunorafael_testrestaurant';

	try{
		$connBd = new PDO("mysql:host={$bd_host};dbname={$bd_name}", $bd_user, $bd_pass);
		
		$connBd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$connBd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	}catch(PDOException $ex){
		die("Impossible de se connecter à la base de données: " . $ex->getMessage());
	}
?>