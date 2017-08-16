<?php
	session_start();

	require_once("bd.php");

	if(!empty($_POST)){
		// Validation
        if(empty($_POST['identifiant'])){
			die("L'identifiant est requis");
		}else if(empty($_POST['motDePasse'])){
			die("Le mot de passe est requis");
		}else if(empty($_POST['confirmationMotDePasse'])){
			die("La confirmation du mot de passe est requise");
		}else if(empty($_POST['nom'])){
			die("Le nom est requis");
		}

		$identifiant = trim($_POST['identifiant']);
		$motDePasse = trim($_POST['motDePasse']);
		$confirmationMotDePasse = trim($_POST['confirmationMotDePasse']);
		$nom = trim($_POST['nom']);

		if ($motDePasse != $confirmationMotDePasse){
			die("Les mots de passe ne correspondent pas");
		}

		// Calculer l'hash du mot de passe
		$hashMotDePasse = hash('sha256', $identifiant.$motDePasse);

        try{
			// Vérifier si l'identifiant est déjà enregistré
			if(!verifierDisponibliteIdentifiant($connBd, $identifiant)){
				die("L'identifiant est déjà enregistré");
			}
			
			$sql = "INSERT INTO clients (identifiant, motDePasse, nom) VALUE(:identifiant, :motDePasse, :nom);"; 
			$paramsSql = array(
				':identifiant' => $identifiant,
				':motDePasse' => $hashMotDePasse,
				':nom' => $nom
			);

			$requete = $connBd->prepare($sql);
			$requete->execute($paramsSql);

			echo "ok";
        }catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
	
	function verifierDisponibliteIdentifiant($bd, $identifiant){
		$sql = "SELECT id FROM clients WHERE identifiant = :identifiant;"; 
		$paramsSql = array(
			':identifiant' => $identifiant
        );
		
		$requete = $bd->prepare($sql);
		$requete->execute($paramsSql);
		
		if($requete->rowCount() > 0){
			return false;
		}else{
			return true;
		}
	}
?>