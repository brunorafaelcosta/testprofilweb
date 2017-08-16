<?php
	if(empty($_SESSION['identifiant'])) 
	{
		exit;
	}

	require_once("bd.php");

	function obtenirReservations($bd, $date = "jour"){
		if ($date == "jour"){
			$date = date("Y-m-d");
		}
		
        try{
			$sql = "SELECT R.id, C.nom, R.dateReservation, R.nombrePersonnes, R.nombrePlacesGratuites FROM reservations AS R"
				. " INNER JOIN clients C ON C.id = R.idClient";
			$paramsSql = array();
			
			if ($date != null){
				$sql = $sql . " WHERE R.dateReservation = :date";
				$paramsSql[':date'] = $date;
			}
			$requete = $bd->prepare($sql);
			$requete->execute($paramsSql);

			$resultat = array();

			while($ligne=$requete->fetch()){
				// Obtenir les tables attribuées
				$sqlTablesAttribuees = "SELECT idTable FROM tablespourreservation WHERE idReservation = :idReservation;";
				$paramsSqlTablesAttribuees = array(
					':idReservation' => $ligne['id']
				);
				$requeteTablesAttribuees = $bd->prepare($sqlTablesAttribuees);
				$requeteTablesAttribuees->execute($paramsSqlTablesAttribuees);

				$resultatTablesAttribuees = array();

				while($table=$requeteTablesAttribuees->fetch()){
					if (array_key_exists('tables', $ligne)){
						$ligne['tables'] = $ligne['tables'] . ", Table " . $table['idTable'];
					}else{
						$ligne['tables'] = "Table " . $table['idTable'];
					}
				}
				
				$resultat[] = $ligne;
			}
			
			return $resultat;
        }catch(PDOException $ex){
			die($ex->getMessage());
		}
	}
	
	function tauxRemplissage($bd, $date = 'jour'){
		if ($date == "jour"){
			$date = date("Y-m-d");
		}
		
		// Obtenir les nombre de personnes pour la date
		$sqlNombrePersonnes = "SELECT SUM(nombrePersonnes) AS sommePersonnes FROM reservations";
		$paramsSqlNombrePersonnes = array();
		
		if ($date != null){
			$sqlNombrePersonnes = $sqlNombrePersonnes . " WHERE dateReservation = :date";
			$paramsSqlNombrePersonnes[':date'] = $date;
		}
		$requeteNombrePersonnes = $bd->prepare($sqlNombrePersonnes);
		$requeteNombrePersonnes->execute($paramsSqlNombrePersonnes);
		$resultat = $requeteNombrePersonnes->fetch();

		$nombrePersonnes = $resultat['sommePersonnes'];
		
		// Obtenir le total de places
		$sqlTotalPlaces = "SELECT SUM(nombrePlaces) AS sommePlaces FROM tables;";
		$requeteTotalPlaces = $bd->prepare($sqlTotalPlaces);
		$requeteTotalPlaces->execute();
		$resultat = $requeteTotalPlaces->fetch();

		$totalPlaces = $resultat['sommePlaces'];
		
		$tauxRemplissage = ($nombrePersonnes * 100) / $totalPlaces;
		
		return number_format($tauxRemplissage, 2);
	}
?>