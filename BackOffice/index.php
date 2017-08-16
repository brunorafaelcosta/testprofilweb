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

	<title>Restaurant - BackOffice</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<link href="css/index.css?version=1" rel="stylesheet">

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
								<li><a class="bt-reservations btn btn-lg btn-primary btn-block" href="reservations.php">R&#233;servations</a></li>
								<li><a class="bt-tables btn btn-lg btn-primary btn-block" href="tables.php">Tables</a></li>
								<li><a class="bt-deconnecter" href="deconnecter.php">Se d&#233;connecter</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-9">
				<h2>R&#233;servations du jour</h2>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Client</th>
								<th>Personnes</th>
								<th>Places gratuites</th>
								<th>Tables</th>
							</tr>
						</thead>
						<tbody>
							<?php
								include_once("php/listeReservations.php");

								$reservations = obtenirReservations($connBd, "jour");

								if (count($reservations) == 0){
									echo("<tr>");
									echo("<td colspan=\"3\">Aucune r&#233;servation</td>");
									echo("</tr>");
								}else{
									foreach($reservations as $reservation){
										echo("<tr>");
										echo("<td>" . $reservation['nom'] . "</td>");
										echo("<td>" . $reservation['nombrePersonnes'] . "</td>");
										echo("<td>" . $reservation['nombrePlacesGratuites'] . "</td>");
										echo("<td>"); if (array_key_exists('tables', $reservation)) echo($reservation['tables']); echo("</td>");
										echo("</tr>");
									}
								}
							?>
						</tbody>
					</table>
				</div>
				
				<h2>Taux de remplissage:</h2>
				<?php  $tauxRemplissage = tauxRemplissage($connBd, "jour"); ?>
				<div class="progress">
					<div class="progress-bar" role="progressbar"
						aria-valuenow="<?php echo $tauxRemplissage;?>"
						aria-valuemin="0" aria-valuemax="100"
						style="width:<?php echo $tauxRemplissage;?>%">
						<?php echo $tauxRemplissage;?>%
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>