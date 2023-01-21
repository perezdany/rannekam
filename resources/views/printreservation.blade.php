@php
  session_start();
  if(empty($_SESSION['pseudo'])) 
  {
    // Si inexistante ou nulle, on redirige vers le formulaire de login
   return view('login');

    exit();
  }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>Facture</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="perfect-scrollbar/perfect-scrollbar.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">

	<link rel="stylesheet" type="text/css" href="css/fmain.css">
<!--===============================================================================================-->


<script>
function imprimer(divName) {
      var printContents = document.getElementById(divName).innerHTML;    
   var originalContents = document.body.innerHTML;      
   document.body.innerHTML = printContents;     
   window.print();     
   document.body.innerHTML = originalContents;
   }
</script>
</head>
<body>
	<div class="limiter">
		<div class="container-table100">
			<div class="wrap-table100">

				<div class="table" id="sectionAimprimer">
					<?php	
						//echo $_GET['id']."<hr>";
						//echo (request('id'));
						//Impression des réservation maintenant, affichage des infos d'ipression
						//if(!empty(request('id')) AND !empty(request('datres')) AND !empty(request('hres')) AND !empty(request('j'))  AND !empty(request('nc')) AND !empty(request('pnc')) AND !empty(request('s')) AND !empty(request('a')) AND !empty( request('m')) AND !empty(request('datar')) AND !empty( request('datdep')) AND !empty(request('ob')))
						
							//tuer les variables sessio pour ne pas avoir de mauvaise surpprise
							session_destroy();
							$date = date("d/m/Y");
							echo'<div class="row header">
							<div class="cell">RESERVATION</div><div class="cell"></div><div class="cell"></div><div class="cell">Abidjan le '.$date.'</div><div class="cell"></div>
							</div>';
							echo'<div class="row">
								<div class="cell" >RESERVATION N°    '.request('id').'</div><div class="cell" ></div><div class="cell"></div><div class="cell" ></div><div class="cell" ></div>
								</div>';
							echo'<div class="row">
								<div class="cell" ></div><div class="cell" ></div><div class="cell" >CLIENT:<b> '.request('nc').'  </b></div><div class="cell" ><b>'.request('pnc').'</b></div><div class="cell"></div>
								</div>';

							echo'<div class="row">
								<div class="cell"><b>Objet de la réservation:</b></div><div class="cell" style="width:250px">'.request('ob').'</div><div class="cell" ></div><div class="cell" ><b>Appartement :</b></div><div class="cell" >'.request('a').'</div>
								</div>';

							echo'<div class="row">
							<div class="cell"><b>Date de la réservation:</b></div><div class="cell">'.request('datres').'</div><div class="cell"></div><div class="cell"><b>Heure de la réservation:</b></div><div class="cell">'.request('hres').'</div><div class="cell"></div>
							</div>';
							echo'<div class="row">
							<div class="cell"><b>Durée du séjour:</b></div><div class="cell">'.request('j').'  jour(s)</div><div class="cell"></div><div class="cell"></div><div class="cell"></div>
							</div>';
							echo'<div class="row">
								<div class="cell" ><b>Date d\'arrivée:</b></div><div class="cell" >'.request('datar').'</div><div class="cell" ><b>Date de départ:</b></div><div class="cell" >'.request('datdep').'</div><div class="cell" ></div>
								</div>';
							echo'<div class="row">
								<div class="cell" ><b>MONTANT DE LA RESERVATION:</b></div><div class="cell" ></div><div class="cell" >'.number_format(request('m'), 0, ',', ' ').'</div><div class="cell" ></div><div class="cell" ></div>
								</div>';//montant de la reservation
							// verification pour dire si la réservation est soldée ou pas
							if(intval(request('s') == 0))
					            {
					            	echo'<div class="row">
									<div class="cell" ></div><div class="cell" ><b>RESERVATION </b></div><div class="cell" ><b>NON SOLDE(E)</b></div><div class="cell" ></div><div class="cell" ></div>
									</div>';
					            }
					            else
					            {
					            	echo'<div class="row">
									<div class="cell" ></div><div class="cell" ></div><div class="cell" ><b>RESERVATION SOLDE(E)</b></div><div class="cell" ></div><div class="cell" ></div>
									</div>';
					            }


						
					

					?>

				</div>
				<br>
			
			</div>
				<div align="center">
					<button type="button" id="impression"  onclick="window.print()" style="width: 250px;  border-radius: 12px;"/>IMPRIMER</button><hr>
					<div align="">
					<button style="width: 250px;  border-radius: 12px;"><a href="/welcome"><i class="fa fa-home" style="font-size: 20px"></i>Acceuil</a></button><br><button style="width: 250px;  border-radius: 12px;"><a href="/register"><i class="fa fa-arrow-left" style="font-size: 20px"></i>Enregistrer une reservation</a></button>
					</div>
				</div>
		</div>
	</div>


<!--===============================================================================================-->	
	<script src="jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="bootstrap/js/popper.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>
</body>
