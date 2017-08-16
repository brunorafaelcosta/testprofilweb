<?php
	session_start();
	
	if(!empty($_SESSION['identifiant'])) 
    {
        header("Location: index.php");
        die("Redirection vers: index.php"); 
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="COSTA Bruno">

	<title>Restaurant - Se connecter</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<link href="css/connecter.css?version=1" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">
		<form id="formSeConnecter" method="post">
			<div id="message"></div>

			<input type="text" id="inputIdentifiant" name="identifiant" class="form-control" placeholder="Identifiant" required autofocus/>
			<input type="password" id="inputMotDePasse" name="motDePasse" class="form-control" placeholder="Mot de passe" required/>

			<button type="submit" class="btn btn-lg btn-primary btn-block">Se connecter</button>
		</form>

		<center>
			<b>Vous n'avez pas de compte?</b>
			<br>
			<a href="enregistrer.php">Cr&#233;er un compte</a>
		</center>
    </div>
	
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script>
	$('document').ready(function(){
		
		$("#formSeConnecter").submit(function(event){
			event.preventDefault();
			
			submitFormSignin();
		});

		function submitFormSignin(){ 
			var data = $("#formSeConnecter").serialize();

			$.ajax({
				type: 'POST',
				url: 'php/connexion.php',
				data: data,
				success: function(response){      
					if(response=="ok"){
						window.location.href = "index.php";
					}else{
						$("#message").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; '+response+'</div>');
					}
				}
			});

			return false;
		}
	});
	</script>
</body>
</html>