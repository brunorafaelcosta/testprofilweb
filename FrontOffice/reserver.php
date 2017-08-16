<?php
	session_start();

	if(empty($_SESSION['identifiant'])) 
	{
		header("Location: connecter.php");
		die("Redirection vers: connecter.php");
	}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="COSTA Bruno">

	<title>Restaurant - R&#233;servation</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<link href="css/reserver.css?version=1" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="sidebar-nav">
					<div class="navbar" role="navigation">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar" style="background-color:#918f8f;"></span>
								<span class="icon-bar" style="background-color:#918f8f;"></span>
								<span class="icon-bar" style="background-color:#918f8f;"></span>
							</button>
							<span class="lb-bienvenu">Bonjour <?php echo $_SESSION['nom']; ?></span>
						</div>

						<div class="navbar-collapse collapse sidebar-navbar-collapse">
							<ul class="nav navbar-nav">
								<li class="lb-carteFidelite">
									Carte de fid&#233;lit&#233;: <span class="lb-nombreTampons">
									<?php
										include_once("php/nombreTampons.php");
										echo obtenirNombreTampons($connBd);
									?> tampons</span>
								</li>
								<li><a class="bt-deconnecter" href="deconnecter.php">Se d&#233;connecter</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-9">
				<form id="formReserver" method="post">
					<h2>Nouvelle r&#233;servation</h2>

					<div id="message"></div>

					<input type="date" id="inputDate" name="date" class="form-control" placeholder="Date" required>
					<input type="number" id="inputNPersonnes" name="nPersonnes" class="form-control" placeholder="Nombre personnes" required min="1">

					<div id="infoPlacesGratuites"></div>

					<button class="btn btn-lg btn-primary btn-block" type="submit" id="btn-reserver">R&#233;server</button>
					<a class="btn btn-sm btn-default btn-block" id="btn-anuller" href="index.php">Anuller</a>
				</form>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript">
		$('document').ready(function(){

			// Fixer la date de réservation minimum à aujourd'hui
			var aujourdhui = new Date();
			var jj = aujourdhui.getDate();
			var mm = aujourdhui.getMonth() + 1; // janvier = 0
			var aaaa = aujourdhui.getFullYear();
			if(jj < 10){
				jj = '0' + jj;
			}
			if(mm < 10){
				mm = '0' + mm;
			}
			aujourdhui = aaaa + '-' + mm + '-' + jj;
			document.getElementById("inputDate").setAttribute("min", aujourdhui);

			// Vérifier si le client a droit à des places gratuites
			$.ajax({
				type: 'GET',
				url: 'php/droitPlacesGratuites.php',
				success: function(response){
					if(response > 0){
						$("#infoPlacesGratuites").html('<div class="alert alert-success"> Vous avez droit &#224; ' + response + ' place(s) gratuite(s)</div>');
					}
				}
			});
			
			$("#formReserver").submit(function(event){
				event.preventDefault();

				submitFormReserver();
			});

			function submitFormReserver(){ 
				$.ajax({
					type: 'POST',
					url: 'php/reservation.php',
					data: $("#formReserver").serialize(),
					success: function(response){      
						if(response=="ok"){
							$("#message").html('<div class="alert alert-success"> La r&#233;servation a &#233;t&#233; faite avec succ&#232;s!</div>');
							setTimeout(' window.location.href = "index.php"; ', 2500);
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