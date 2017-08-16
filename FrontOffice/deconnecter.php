<?php 
	session_start();

	unset($_SESSION['id']);
	unset($_SESSION['identifiant']);
	unset($_SESSION['nom']);

	if(session_destroy()){
		header("Location: connecter.php");
		die("Redirection vers: connecter.php");
	}
?>