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

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>

  <title> Admin | Reservations</title>

  <!-- Custom fonts for this template -->
  <link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


  <link rel="stylesheet" href="css/tablestyle.css">
  <link rel="stylesheet" href="css/astyle.css">



  <!--code javascript pour imprimer-->
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
<body class="bg-gradient-danger">
 

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
          <div class="row">
            <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
            <div class="col-lg-12">
              <!--formulaire pour la modifiaction de reservation-->
              
              <div class="p-5">
                <hr/><div class="text-center"><h1 class="h4 text-gray-900 mb-4">Payment de la réservation</h1></div>
                <form class="user" method="post" action="/buy_reservation">
                  {{csrf_field()}}
                  @php
                    //on recupere les elemnts aussi de la derniere facture payée
                    $voirm = DB::select("SELECT SQL_NO_CACHE r.id_reserv, r.id_appart, r.nb_adlt, r.nb_enf, r.dat_arriv, r.nb_jr, c.nom_clt, c.pnom_clt, a.lib_appart, r.mont_reserv, f.rest_a_pay FROM factures f, reservations r, appartements a, clients c WHERE r.id_clt=c.id_clt AND r.id_appart=a.id_appart AND f.id_reserv=r.id_reserv AND r.id_clt='".request('id_clt')."' AND r.id_reserv='".request('id')."' ORDER BY f.hr_emi DESC LIMIT 1");
                      //dd($voirm->rest_a_pay);
                    //
                    //var_dump($voirm);
                  @endphp
                  <div class="form-group row">
                   
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      RESERVATION N°: <b>{{ request('id') }}</b><input type="hidden" value="{{request('id')}}" name="id_reservation" class="form-control form-control-user" id="exampleFirstName">
                    </div>
                    <div class="col-sm-6">
                      @foreach($voirm as $voirm)
                        MONTANT DE LA RESERVATION: <b>{{$voirm->mont_reserv}} </b></label><input type="hidden" value="{{$voirm->mont_reserv}}" name="montant"class="form-control form-control-user" id="exampleLastName" >
                      @endforeach
                      
                    </div>
                  </div>
                    
                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0"><br>
                      <input type="text" name="m" class="form-control form-control-user" required placeholder="Montant A Payer:">
                    </div>
                    <div class="col-sm-6">
                      @php
                        //on recupere le reste a payer
                        $voirm = DB::select("SELECT SQL_NO_CACHE f.rest_a_pay FROM factures f, reservations r, appartements a, clients c WHERE r.id_clt=c.id_clt AND r.id_appart=a.id_appart AND f.id_reserv=r.id_reserv AND r.id_clt='".request('id_clt')."' AND r.id_reserv='".request('id')."' ORDER BY f.hr_emi DESC LIMIT 1");
                      @endphp
                      @foreach($voirm as $voirm)
                        MONTANT RESTANT DE LA RESERVATION<input type="text" class="form-control form-control-user" value="{{$voirm->rest_a_pay}}" disabled="disabled">
                      @endforeach
                      
                    </div>
                   
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-8 mb-3 mb-sm-0"><br>
                      Mode de payement:
                      <br>
                      <select  class="form-control" name="pay_mod" style="width:200px">
                      <@php
                        //on afficher les différents mode de payement de la  base
                        $query = DB::select("SELECT * FROM payments");
                      
                        foreach ($query as $query) 
                        { 
                          echo'<option value="'.$query->id_pay.'">'.$query->mod_pay.'</option>';
                        }
                      @endphp
                       
                      </select>
                    </div>
                   
                    <div class="col-sm-4"></div>
                  </div>
                  <button type="submit" name="fact" class="btn btn-primary">PAYER</button>
                  </fieldset>
                </form>
                <p style="color:red;">@if(isset($messageissue)){{$messageissue}}@endif</p>
                <p style="color:green;">@if(isset($message)){{$message}}@endif</p>

                <div class="text-center">
                  <a class="small" href="/reservations"><font style="font-size: 20px">Retour aux réservations</font></a> OU <a class="small" href="/customers"><font style="font-size: 20px">Retour a la gestion des clients</font></a>
                </div>
              </div>
            </div>
          </div>
        </div>
    

          <!-- Nested Row within Card Body -->
       

    </div>

  </div>


 <!-- Bootstrap core JavaScript-->
  <script src="jquery/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="datatables/jquery.dataTables.min.js"></script>
  <script src="datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

</body>
</html>