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

  <title>Edit - reservation</title>

  <!-- Custom fonts for this template-->
  <link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>


<script language="javascript">

   function numStr(a, b) //se^parateur de millier
    {
        a = '' + a;
        b = b || ' ';
        var c = '',
            d = 0;
        while (a.match(/^0[0-9]/)) {
          a = a.substr(1);
        }
        for (var i = a.length-1; i >= 0; i--) {
          c = (d != 0 && d % 3 == 0) ? a[i] + b + c : a[i] + c;
          d++;
        }
        return c;
    }

    function make_rem()//code pour faire le calcule de la remise et afficher
    {
      //recuperation des valeurs
      var jr = document.getElementById('j').value;
      var rr = document.getElementById('rem').value;
      var ta = document.getElementById('t').value;
      var taa = document.getElementById('tt').value;
      var content = document.getElementById('resultat')
      //conversion en entier
      r = Number(rr);
      j = Number(jr);
      //calcul du montant
      var mont = 0;
      if(j==28 || j==29)
      {
        mont =  Number(taa);
      }

      if(j==30)
      {
          mont =  Number(taa);
      }
      else
      {
        mont =  Number(ta)*j;
      }
      var mr = Math.round(mont - ((mont*(r/100))/1.18));
      var f = numStr(mr, ' ');

      //afficher le resultat dans notre balise div qui a l'id content
      resultat = document.createTextNode('Montant après remise: ' + f +' FCFA');
      while (content.firstChild) //pour que si y des noeuds texts precedent on supprime
      {
        content.removeChild(content.firstChild);
      }
      content.appendChild(resultat);
    }

    function recup_index()//code pour recuperer l'index de l'apparttement
    {
      app = document.form.ap.selectedIndex;//recuperer l'element
      i = document.form.ap.options[app].value; //recuperer la valeur
      //avant il faut recupérer les valeur de chaque champ préremplie pour pour pouvoir les réafficher quand on renvoyera l'id
      /*name = document.getElementById("name").value;
      pname = document.getElementById("pname").value;
      adlt = document.getElementById("adlt").value;
      enf = document.getElementById("enf").value;
      date = document.getElementById("date").value;
      days = document.getElementById("j").value;*/
      window.location.href = "mod.php?i="+i//+"&name="+name+"&pname="+pname+"&mail="+adlt+"&enf="+enf+"&date="+date+"&days="+days; //passer en url l'index de l'appart
    }
  </script>

<body class="bg-gradient-danger">
 

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
          <div class="row">
            <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
            <div class="col-lg-12">
              <!--formulaire pour la modifiaction de reservation-->
              <div class="p-5">
                <hr/>
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4">Modification de réservation</h1>
                </div>
               
                <form class="user" method="post" name="form" action="/edit_reservation">
                  {{ csrf_field() }}
                    <div class="form-group row">
                      <div class="col-sm-6 mb-3 mb-sm-0">
                        Nom:
                        <input type="text" name="nom"  class="form-control form-control-user" id="exampleFirstName" disabled  id="name" value="@if(isset($nom_client)){{$nom_client}}@endif" >
                      </div>
                      <div class="col-sm-6">
                      Prénom(s):
                      <input type="text" name="pnom" class="form-control form-control-user" id="exampleLastName" id="pname" disabled value="@if(isset($pnom_client)){{$pnom_client}}@endif" >
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-6 mb-3 mb-sm-0">
                        APPARTEMENT:
                        <select name="ap" class="form-control form-control" id="app">
                          <option value="@if(isset($id_appart)){{$id_appart}}@endif">@if(isset($designation_appart)){{$designation_appart}}@endif</option>
                          @php
                            //DB::table('appartements')->get('id_appart');
                            use \App\Models\Appartement;
                            $aujourd = date('Y-m-d');//prendre la date de today
                            //prendre a chaque fois les ids des apparts
                            $appart = Appartement::all();
                            //meme processus que dans la page d'accueil
                          @endphp
                          @foreach($appart as $appart)
                            @php
                              //use DB;
                              //Selectionner les id des appartement qui sont oqp dans la table reservation 
                              $check_dispo = DB::table('reservations')->where('dat_arriv', '<=', $aujourd)->where('dat_dep', '>=', $aujourd)->where('id_appart', $appart->id_appart)->get();
                              
                              if(count($check_dispo) == 0) //l'appart est libre donc on le selectionne pour afficher
                              {
                                $app_dispo = Appartement::whereId_appart($appart->id_appart)->get();
                                foreach ($app_dispo as $app_dispo) 
                                {
                                  echo'<option value="'.$app_dispo->id_appart.'">'.$app_dispo->lib_appart.' |tarfis: jour= '.$app_dispo->tar_jour.'| Mois= '.$app_dispo->tar_mois.'|</option>';
                                }
                              }
                            @endphp                     
                          @endforeach
                        <select><br>
                        </div>
                        <div class="col-sm-6">
                        Nombre d'adultes:
                          <select name="adlt" style="width:200px; height: 70px>" class="form-control" id="adlt">';
                            <option value="@if(isset($nom_client)){{$nb_adultes}}@endif" >@if(isset($nom_client)){{$nb_adultes}}@endif</option>
                            @php
                              
                              for($a=0; $a<=10; $a++)
                              {
                                echo'<option value="'.$a.'" >'.$a.'</option>';
                              }
                            @endphp
                          </select>
                        </div>
                      </div>
                         
                    <div class="form-group row">
                      <div class="col-sm-6 mb-3 mb-sm-0">
                        Nombre d'enfants ex:0,1,2...
                        <select name="enf" style="width:250px; height: 70px>" class="form-control" id="enf">';
                          <option value="@if(isset($nb_enfants)){{$nb_enfants}}@endif" >@if(isset($nb_enfants)){{$nb_enfants}}@endif</option>
                          @php
                            for($b=0; $b<=10; $b++)
                            {
                              echo'<option value="'.$b.'" >'.$b.'</option>';
                            }
                          @endphp
                        </select>
                      </div>
                      <div class="col-sm-6">
                       Date d'arrivée:<input type="date"/ name="ar" class="form-control form-control-user" id="date"  value="@if(isset($date_arrivee)){{$date_arrivee}}@endif" >
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-6 mb-3 mb-sm-0">
                        Nombre de jours ex:0,1,2...
                        <select name="jr" style="width:250px" height: 70px class="form-control" id="j">
                        <option value="@if(isset($jours)){{$jours}}@endif" >@if(isset($jours)){{$jours}}@endif</option>
                          @php
                            for($c=0; $c<=30; $c++)
                            {
                              echo'<option value="'.$c.'" >'.$c.'</option>';
                            }
                          @endphp
                        </select>
                      </div>
                      <div class="col-sm-6">
                        <input type="text" name="id_reserv" value="@if(isset($id_reservation)){{$id_reservation}}@endif" style="display: none;">
                      </div>
                    </div>
                    @php
                      if(isset($statut_reservation) AND isset($remise) AND intval($statut_reservation) != 0)
                      {
                        echo'<div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                          Remise(<b>%</b>):
                          <input type="text" name="remise" style="width:100px" height: 50px class="form-control" id="rem" value="'.$remise.'">
                        </div>
                        <div class="col-sm-6">
                          Nouveau montant (défintif de la réservation):
                          <input type="text" name="montant" style="width:310px" height: 50px class="form-control" id="mont" >
                        </div>
                      </div>';
                      }
                      else
                      {
                        if(isset($montant_reservation))

                        echo'<div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                          Remise(<b>%</b>):
                          <input type="text" name="remise" style="width:100px" height: 50px class="form-control" id="rem" value="0">
                        </div>
                        <div class="col-sm-6">
                          Nouveau montant (défintif de la réservation):
                          <input type="text" name="montant" style="width:310px" height: 50px class="form-control" id="mont" value="'.$montant_reservation.'">
                        </div>
                      </div>';

                      }
                    @endphp
                    
                    <div class="form-group row">
                      <div class="col-sm-6 mb-3 mb-sm-0">
                      <button type="submit" name="mod" class="btn btn-primary">MODIFIER</button></div>
                      <div class="col-sm-6"><button type="reset" name="reset" class="btn btn-danger">ANNULER</button>
                    </div>
                    @if(isset($message))
                      <p style="color: royalblue; font-size: 20px">{{$message}}</p>
                    @endif
                <hr>
                  <div class="text-center">
                    <a class="small" href="/reservations"><font style="font-size: 20px">Retour aux réservations</font></a> OU <a class="small" href="/customers"><font style="font-size: 20px">Retour a la gestion des clients</font></a>
                  </div>
                </form>
                <hr>
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