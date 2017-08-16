<?php
	session_start();

	require_once("bd.php");

	if(!empty($_POST)){
		// Validation
        if(empty($_POST['identifiant'])){
			echo "L'identifiant est requis";
		}else if(empty($_POST['motDePasse'])){
			echo "Le mot de passe est requis";
		}

		$identifiant = trim($_POST['identifiant']);
		$motDePasse = trim($_POST['motDePasse']);

		$sql = "SELECT id, identifiant, motDePasse, nom FROM clients WHERE identifiant = :identifiant;"; 
		$paramsSql = array(
			':identifiant' => $identifiant
        );

        try{
			$requete = $connBd->prepare($sql);
			$requete->execute($paramsSql);
			
			$resultat = $requete->fetch();
			$nLignes = $requete->rowCount();

			// Calculer l'hash du mot de passe (identifiant + mot de passe)
			$hashMotDePasse = hash('sha256', $resultat['identifiant'].$motDePasse);

			if($nLignes > 0 && $resultat['motDePasse']==$hashMotDePasse){
				// Connecté avec succès
				$_SESSION['id'] = $resultat['id'];
				$_SESSION['identifiant'] = $resultat['identifiant'];
				$_SESSION['nom'] = $resultat['nom'];
				echo "ok";
			}else{
				echo "Les informations d'identification sont incorrectes";
			}
        }catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
?>