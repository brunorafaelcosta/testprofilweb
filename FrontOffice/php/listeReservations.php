<?php
	if(empty($_SESSION['identifiant'])) 
	{
		exit;
	}
	
	require_once("bd.php");

	$sql = "SELECT dateReservation, nombrePersonnes, nombrePlacesGratuites FROM reservations WHERE idClient = :idClient AND dateReservation >= CURDATE();"; 
	$paramsSql = array(
		':idClient' => $_SESSION['id']
	);

	try{
		$requete = $connBd->prepare($sql);
		$requete->execute($paramsSql);
		
		$resultat = array();
		
		while($ligne=$requete->fetch()){
			$resultat[] = $ligne;
		}
		
		$nLignes = $requete->rowCount();
		if ($nLignes == 0){
			echo("<tr>");
			echo("<td colspan=\"3\">Aucune r&#233;servation</td>");
			echo("</tr>");
		}else{
			foreach($resultat as $reservation){
				echo("<tr>");
				echo("<td>" . $reservation['dateReservation'] . "</td>");
				echo("<td>" . $reservation['nombrePersonnes'] . "</td>");
				echo("<td>" . $reservation['nombrePlacesGratuites'] . "</td>");
				echo("</tr>");
			}
		}
	}catch(PDOException $ex){
		echo($ex->getMessage());
	}
?>