<?php
	session_start();

	require_once("bd.php");
	require_once("nombreTampons.php");

	if(!empty($_POST)){
		// Validation
        if(empty($_POST['date'])){
			die("La date est requise");
		}else if(empty($_POST['nPersonnes'])){
			die("Le nombre de personnes est requis");
		}

		$date = date("Y-m-d", strtotime($_POST['date']));
		$nPersonnes = $_POST['nPersonnes'];

        try{
			$tablesDisponibles = obtenirTablesDisponibles($connBd, $date);
			
			// Vérifier s'il y a assez de places pour faire la réservation
			$totalPlacesDisponibles = 0;
			foreach ($tablesDisponibles as $table){
				$totalPlacesDisponibles += $table['nombrePlaces'];
			}
			if ($totalPlacesDisponibles < $nPersonnes){
				throw new Exception("Il n'y a pas assez de places disponibles pour faire la réservation. ".$totalPlacesDisponibles." places disponibles pour cette date");
			}
			
			$posibilitesCombinaisonsPlaces = calculerPosibilitesCombinaisonsPlaces($tablesDisponibles);
			$meilleuresCombinaisons = calculerMeilleureCombinaison($posibilitesCombinaisonsPlaces, $nPersonnes);
			
			if ($meilleuresCombinaisons != null){
				enregistrerReservation($connBd, $nPersonnes, $date, $meilleuresCombinaisons);
			}else{
				throw new Exception("Aucune combinaison de tables trouvée pour la réservation");
			}
			
			echo "ok";
        }catch(Exception $ex){
			echo $ex->getMessage();
		}
	}

	function obtenirTablesDisponibles($bd, $date){
		// Obtenir toutes les tables disponibles pour la date
		$sql = "SELECT * FROM tables WHERE id NOT IN"
				. " (SELECT tpr.idTable FROM tablespourreservation tpr"
				. " LEFT JOIN reservations r ON tpr.idReservation = r.id"
				. " WHERE r.dateReservation = :date);";
		$paramsSql = array(
			':date' => $date
        );
		
		$requete = $bd->prepare($sql);
		$requete->execute($paramsSql);
		
		$resultat = array();
		
		while($ligne=$requete->fetch()){
			$resultat[] = $ligne;
		}
		
		$nLignes = $requete->rowCount();
		if ($nLignes == 0){
			throw new Exception("Il n'y a pas de tables disponibles pour faire la réservation");
		}
		
		return $resultat;
	}
	
	function calculerPosibilitesCombinaisonsPlaces($tables){
		$resultat = array(array());
		
		foreach ($tables as $table){
			foreach ($resultat as $combinaison){
				$merge = array_merge(array($table), $combinaison);
				
				if (combinaisonDejaExistante($merge,$resultat) == false){
					array_push($resultat, $merge);
				}
			}
		}
		
		return $resultat;
	}

	function combinaisonDejaExistante($combinaison, $liste){
		// Extraire que le nombre de places pour pouvoir comparer
		$nPlacesCombinaison = array_column($combinaison, 'nombrePlaces');
		
		for($i=0; $i<count($liste); $i++){
			// Faire la même chose ici pour que je puisse comparer les deux
			$nPlacesListe = array_column($liste[$i], 'nombrePlaces');
			
			// http://stackoverflow.com/a/12867389
			$dejaExistante = (count(array_intersect($nPlacesCombinaison,$nPlacesListe)) == count($nPlacesCombinaison) && count(array_intersect($nPlacesCombinaison,$nPlacesListe)) == count($nPlacesListe));
			if($dejaExistante){
				return true;
			}
		}
		
		return false;
	}
	
	function calculerMeilleureCombinaison($posibilitesCombinaisonsPlaces, $nombrePlacesSouhaitees){
		$meilleureDifference = null;
		$meilleureCombinaison = null;
		
		foreach($posibilitesCombinaisonsPlaces as $combinaison){
			$sommePlaces = 0;
			foreach($combinaison as $table){
				$sommePlaces += $table['nombrePlaces'];
			}
			
			$difference = $sommePlaces - $nombrePlacesSouhaitees;
			
			if ($difference == 0){
				return $combinaison;
			} else if ($difference > 0){
				if ($meilleureDifference == null || $difference < $meilleureDifference){
					$meilleureDifference = $difference;
					$meilleureCombinaison = $combinaison;
				}
			}
		}
		
		return $meilleureCombinaison;
	}

	function enregistrerReservation($bd, $nPersonnes, $date, $meilleuresCombinaisons){
		// Calculer le droit aux places gratuites
		$nTampons = obtenirNombreTampons($bd);
		$nPlacesGratuites = floor($nTampons /10);
		
		// Enregistrer la réservation
		$sql = "INSERT INTO `reservations` (`idClient`, `nombrePersonnes`, `dateReservation`, `nombrePlacesGratuites`) VALUES(:idClient, :nPersonnes, :date, :nPlacesGratuites);"; 
		$paramsSql = array(
			':idClient' => $_SESSION['id'],
			':nPersonnes' => $nPersonnes,
			':date' => $date,
			':nPlacesGratuites' => $nPlacesGratuites
		);

		$requete = $bd->prepare($sql);
		$requete->execute($paramsSql);
		
		$idReservation = $bd->lastInsertId();
		
		// Enregistrer les tables atribuées
		foreach($meilleuresCombinaisons as $meilleureCombinaison){
			$sql2 = "INSERT INTO `tablespourreservation` (`idReservation`, `idTable`) VALUES (:idReservation, :idTable);"; 
			$paramsSql2 = array(
				':idReservation' => $idReservation,
				':idTable' => $meilleureCombinaison['id']
			);

			$requete2 = $bd->prepare($sql2);
			$requete2->execute($paramsSql2);
		}
		
		// Mettre à jour le nombre de tampons de la carte de fidélite
		mAJTamponsCarteFidelite($bd, $nPersonnes, $nPlacesGratuites);
	}
	
	function mAJTamponsCarteFidelite($bd, $nPersonnesReservation, $nPlacesGratuitesAttribuees){
		$nTampons = obtenirNombreTampons($bd);
		
		// Enlever les tampons si des places gratuites ont été attribuées
		$nouveauNTampons = $nTampons - ($nPlacesGratuitesAttribuees * 10);
		
		// Attribuer des tampons par rapport au nombre de personnes de la réservation
		$nouveauNTampons = $nouveauNTampons + $nPersonnesReservation;
		
		$sql = "UPDATE `clients` SET `nombreTamponsCarteFidelite` = :nTampons WHERE `id` = :id;"; 
		$paramsSql = array(
			':id' => $_SESSION['id'],
			':nTampons' => $nouveauNTampons
		);

		$requete = $bd->prepare($sql);
		$requete->execute($paramsSql);
	}
?>