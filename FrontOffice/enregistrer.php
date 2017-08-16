<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="COSTA Bruno">

	<title>Restaurant - S'enregistrer</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<link href="css/enregistrer.css?version=1" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">
		<form id="formEnregistrer" method="post">
			<div id="message"></div>
		
			<input type="text" id="inputIdentifiant" name="identifiant" class="form-control" placeholder="Identifiant" required autofocus>
			<input type="password" id="inputMotDePasse" name="motDePasse" class="form-control" placeholder="Mot de passe" required>
			<input type="password" id="inputConfirmationMotDePasse" name="confirmationMotDePasse" class="form-control" placeholder="Confirmation du mot de passe" required>
			<input type="text" id="inputNom" name="nom" class="form-control" placeholder="Nom" required>
			<button class="btn btn-lg btn-primary btn-block" type="submit" id="btn-register">S'enregistrer</button>
		</form>

		<center>
			<b>Vous avez d&#233;j&#224; un compte?</b>
			<br>
			<a href="connecter.php">Se connecter</a>
		</center>
    </div>
	
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script>
	$('document').ready(function(){

		// Validation des mot de passe
		document.getElementById("inputMotDePasse").onchange = confirmerMotsDePasse;
		document.getElementById("inputConfirmationMotDePasse").onchange = confirmerMotsDePasse;
		function confirmerMotsDePasse(){
			var confirmationMotDePasse = document.getElementById("inputConfirmationMotDePasse").value;
			var motDePasse = document.getElementById("inputMotDePasse").value;

			if(motDePasse != confirmationMotDePasse){
				document.getElementById("inputConfirmationMotDePasse").setCustomValidity("Les mots de passe ne correspondent pas");
			}else{
				document.getElementById("inputConfirmationMotDePasse").setCustomValidity('');
			}
		}

		$("#formEnregistrer").submit(function(event){
			event.preventDefault();

			submitFormEnregistrer();
		});

		function submitFormEnregistrer(){ 
			$.ajax({
				type: 'POST',
				url: 'php/enregistrement.php',
				data: $("#formEnregistrer").serialize(),
				success: function(response){      
					if(response=="ok"){
						$("#message").html('<div class="alert alert-success"> Vous avez &#233;t&#233; enregistr&#233; avec succ&#232;s!</div>');
						setTimeout(' window.location.href = "connecter.php"; ', 2500);
					}else{
						$("#message").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; '+response+' !</div>');
					}
				}
			});

			return false;
		}
	});
	</script>
</body>
</html>