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

					$num_mois = date('n');
					if(isset($_SESSION['appart']) AND isset( $_SESSION['nom']) AND isset($_SESSION['pnom']) AND isset($_SESSION['date']) AND isset($_SESSION['newdate1']) AND isset($_SESSION['montant']) AND isset($_SESSION['desig'])  AND isset( $_SESSION['j']) AND isset($_SESSION['reserv']))
						{
							echo'<div class="row header" style="font-weight:bold; color: #292929;">
							<div class="cell" style="font-weight:bold; color: #292929;">FactureN°'.$_SESSION['fact'].'</div><div class="cell"></div><div class="cell"></div><div class="cell" style="font-weight:bold; color: #292929;">Abidjan le '.$_SESSION['date'].'</div><div class="cell"></div>
							</div>';
							echo'<div class="row" style="font-weight:bold; color: #292929;">
								<div class="cell" ></div><div class="cell" ></div><div class="cell" style="font-weight:bold; color: #292929;">Doit: '.$_SESSION['nom'].'  '.$_SESSION['pnom'].'</div><div class="cell" ></div><div class="cell" ></div>
								</div>';
							echo'<div class="row" style="font-weight:bold; color: #292929;">
								<div class="cell" style="font-weight:bold; color: #292929;"><u>Objet</u>:</div><div class="cell"  style="font-size:9.5px">Location d\'appartement meublé</div><div class="cell" ></div><div class="cell" ></div><div class="cell" ></div>
								</div>';
							echo'<div class="row header" style="font-weight:bold; color: #292929;">
								<div class="cell" style="font-weight:bold; color: #292929;">DESIGNATION</div><div class="cell" style="font-weight:bold; color: #292929;">UNITE</div><div class="cell" style="font-weight:bold; color: #292929;">QUANTITE</div><div class="cell" style="font-weight:bold; color: #292929;">PRIX UNITAIRE</div><div class="cell" style="font-weight:bold; color: #292929;">TOTAL</div>
								</div>';
							
							echo'<div class="row" style="font-weight:bold; color: #292929;">
							<div class="cell" style="font-weight:bold; color: #292929;">'.$_SESSION['desig'].'</div>
							<div class="cell" style="font-weight:bold; color: #292929;">U</div>
							<div class="cell" style="font-weight:bold; color: #292929;">'.$_SESSION['j'].' JOURS</div>
							<div class="cell" style="font-weight:bold; color: #292929;"><b>'.number_format(($_SESSION['p1']/1.18), 0, ',', ' ').'</b></div>';
							if($_SESSION['j']<30)
							{
								echo'<div class="cell" style="font-weight:bold; color: #292929;"><b>'.number_format(round((($_SESSION['p1']*$_SESSION['j'])/1.18), 0, PHP_ROUND_HALF_DOWN), 0, ',', ' ').'</b></div>
							</div>';
							}
							else
							{
								echo'<div class="cell" style="font-weight:bold; color: #292929;"><b>'.number_format(round((($_SESSION['p1']/1.18)), 0, PHP_ROUND_HALF_DOWN), 0, ',', ' ').'</b></div>
							</div>';
							}
							
							//montant de la remise
							
							if($_SESSION['j']<30)
							{
								$mdr =(($_SESSION['p1'])*$_SESSION['j'])*($_SESSION['remise']/100);
							}
							else
							{
								$mdr =(($_SESSION['p1']))*($_SESSION['remise']/100);
							}

							echo'<div class="row" style="font-weight:bold; color: #292929;">
								<div class="cell" ></div><div class="cell" ></div><div class="cell" ></div><div class="cell" style="font-weight:bold; color: #292929;">TVA 18%</div>';

							if($_SESSION['j']<30)
							{
								echo'<div class="cell" style="font-weight:bold; color: #292929;"><b>'.number_format(round( ( ( $_SESSION['p1']*$_SESSION['j'] )/1.18 )*0.18, 0, PHP_ROUND_HALF_DOWN), 0, ',', ' ').'</b></div>';
							}
							else
							{
								echo'<div class="cell" style="font-weight:bold; color: #292929;"><b>'.number_format(round( ( $_SESSION['p1']/1.18 )*0.18, 0, PHP_ROUND_HALF_DOWN), 0, ',', ' ').'</b></div>';
					
							}	
							//echo'<div class="cell" style="font-weight:bold; color: #292929;"><b>'.number_format(round(($_SESSION['montant']*0.18), 0, PHP_ROUND_HALF_DOWN), 0, ',', ' ').'</b></div>';
							echo'</div>';

							echo'<div class="row" style="font-weight:bold; color: #292929;">
								<div class="cell" ></div><div class="cell" ></div><div class="cell" ></div><div class="cell" style="font-weight:bold; color: #292929;">MONTANT DE LA REMISE</div><div class="cell" style="font-weight:bold; color: #292929;"><b>'.number_format(round($mdr, 0, PHP_ROUND_HALF_DOWN), 0, ',', ' ').'</b></div>
								</div>';

							echo'<div class="row" style="font-weight:bold; color: #292929;">
								<div class="cell" ></div><div class="cell" ></div><div class="cell" ></div><div class="cell" style="font-weight:bold; color: #292929;">MONTANT TOTAL TTC</div>';
							echo'<div class="cell" style="font-weight:bold; color: #292929;"><b>'.number_format($_SESSION['montant'], 0, ',', ' ').'</b></div>';
							
							echo'</div>';

							

							//$lettre=new ChiffreEnLettre();
							//$l = $lettre->Conversion($_SESSION['montant'));
							//Formater de sorte a afcciher en lettre
							$fmt = new NumberFormatter( 'en_EN', NumberFormatter::DECIMAL ); //créer un format et comme notre nombre est décimal donc décimal
							$num = $fmt->parse($_SESSION['montant']);
							$int = $fmt->parse($num, NumberFormatter::TYPE_INT32);//ensuite rendre entier
							$f = new NumberFormatter("fr_FR", NumberFormatter::SPELLOUT);// et enfin ecrire en chifre
							$w = $f->format($int);
							echo'<div class="row" style="font-weight:bold; color: #292929;">
								<div class="cell" >Arrêter la présente  facture</div><div class="cell" > à la somme de :</div><div class="cell" style="font-weight:bold; color: #292929;"><b>'.ucfirst($w).' FCFA</b></div><div class="cell" ></div><div class="cell" ></div>
								</div>';
							echo'<div class="row">
								<div class="cell" ></div><div class="cell" ></div><div class="cell" ></div><div class="cell" ><b><u>La direction générale</u></b></div><div class="cell" ></div>
								</div>';
							echo'<div class="row header" style="font-weight:bold; color: #292929;">
								<div class="cell" ></div><div class="cell" ></div><div class="cell" style="font-weight:bold; color: #292929;">Nos références bancaires</div><div class="cell" ></div><div class="cell" ></div>
								</div>';
							echo'<div class="row">
								<div class="cell" >Domiciliation:</div><div class="cell" >BANK OF AFRICA</div><div class="cell" ></div><div class="cell" ></div><div class="cell" ></div>
								</div>';
							echo'<div class="row">
								<div class="cell" >IBAN:</div><div class="cell" ></div><div class="cell"  style="width: 200px">CI93 CI03 2010 1300 6099 2100 0584</div><div class="cell" ></div><div class="cell" ></div>
								</div>';
							echo'<div class="row">
								<div class="cell" >Banque</div><div class="cell" ></div><div class="cell" >Guichet</div><div class="cell" >Compte</div><div class="cell" >Clé RIB</div>
								</div>';
							echo'<div class="row">
								<div class="cell" >CI032</div><div class="cell" ></div><div class="cell" >1013</div><div class="cell" >6099210005</div><div class="cell" >84</div>
								</div>';

							//$_SESSION['mdr'] = $mdr;
						}	
						else
						{
							echo'<H1>pas de facture à imprimer</H1>';
						}

				?>

				</div>
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