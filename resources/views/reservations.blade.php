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
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

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

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php include('AdminSidebar.txt');?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column bg-gradient-danger">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                  <!--<form method="post">
                    <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" type='submit' name="export"><i class="fas fa-download fa-sm text-white-50"></i>Exporter la base de données(.sql)</button>
                  
                  </form>-->
                  <div id="fond"></div>
                  <div id="popup">
                   <div id="close"></div>
                  </div>
                  <!-- Sidebar Toggle (Topbar) -->
                  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                  </button>

               
                  <!-- Topbar Navbar -->
                  <ul class="navbar-nav ml-auto">

                      <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                      <li class="nav-item dropdown no-arrow d-sm-none">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-search fa-fw"></i>
                        </a>
                        <!-- Dropdown - Messages -->
                        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                          <form class="form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                              <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" name="search">
                                  <i class="fas fa-search fa-sm"></i>
                                </button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </li>

                      <div class="topbar-divider d-none d-sm-block"></div>

                      <!-- Nav Item - User Information -->
                      <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                            @if(isset($pseudo)){{$pseudo}}
                            @elseif(isset($_SESSION['pseudo'])){{$_SESSION['pseudo']}}@endif 
                          </span>
                          <img class="img-profile rounded-circle" src="images/icons/user.png">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                           <!--<a class="dropdown-item" href="../admin/admin.php">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            listes des Admins
                          </a>-->
                          <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="" data-toggle="modal" data-target="#logoutModal">
                              <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                              Deconnexion
                            </a>
                        </div>
                      </li>

                  </ul>
                </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
            <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="imprimer('section_to_print');"><i class="fas fa-download fa-sm text-white-50"></i> Générer le rapport</button>
          </div>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Gestions des réservations</h6>
            </div>
            <div class="card-body" > 
              <div class="table-responsive" id="section_to_print">
                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                  <thead>
                    <tr><th>Date | Heure de réservation</th><th>Appartement</th><th>Client</th><th>Montant</th><th>Etat de la réservation</th><th>Action</th></tr>
                  </thead>
                  <tbody>
                    @php
                      use \App\Http\Controllers\ReservationsController;
                      $voir = (new ReservationsController())->NonPayedReservation();//reservations en cours
                      foreach($voir as $voir)
                      {
                        $timestampd = strtotime($voir->dat_reserv);//recuperation du timestanp de la date donnée
                        $new_format = date("d/m/Y", $timestampd);//changement du format
                        echo'<tr><td>'.$new_format.'   |   '.$voir->hr_reserv.'</td><td>'.$voir->lib_appart.'</td><td>'.$voir->title.' '.$voir->nom_clt.' '.$voir->pnom_clt.'</td><td>'.number_format($voir->mont_reserv, 0, ',', ' ').'</td>';
                        if(intval($voir->statut == 0))
                          {echo'<td>non Soldée</td>';}
                        else
                          {echo'<td>Soldée</td>';}
                        echo'
                          <td align="center">
                          <button type="button" class="collapsible btn btn-circle bg-gradient-success"><font color="white"><i class="fa fa-angle-down"></i></font></button>
                          <div class="content" style="display:none;">
                          <form action="/reservations" method="post">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="text" value="'.$voir->id_reserv.'" style="display: none;" name="id"><button class="btn btn-circle bg-gradient-danger" type="submit" name="delres"><font color="white"><i class="fa fa-trash"></i></font></button>
                          </form>
                           <form action="/edit_reservation" method="get" >
                            <div style="display: none;">
                               <input value="'.$voir->id_reserv.'" name="id" type="text"><input type="text" name="id_client" value="'.$voir->id_clt.'"><input type="text" name="objet" value="'.$voir->objet.'">
                            </div>  
                            <button class="btn btn-circle bg-gradient-info"><font color="white"><i class="fa fa-edit"></i></font></button>
                          </form>
                          <form action="/buy_reservation" method="get" >
                            <div style="display: none;">
                               <input value="'.$voir->id_reserv.'" name="id" type="text">
                               <input value="'.$voir->id_clt.'" name="id_clt" type="text">
                            </div>  
                            <button class="btn btn-circle bg-primary"><font color="white"><i class="fa fa-dollar-sign"></i></font>
                            </button>
                          </form>
                          <form action="/printreservation" method="get">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <div style="display:none;">
                              <input value="'.$voir->id_reserv.'" name="id" type="text">
                              <input value="'.$voir->dat_reserv.'" name="datres" type="text">
                              <input value="'.$voir->hr_reserv.'" name="hres" type="text">
                              <input value="'.$voir->nb_jr.'" name="j" type="text">
                              <input value="'.$voir->nom_clt.'" name="nc" type="text">
                              <input value="'.$voir->pnom_clt.'" name="pnc" type="text">
                               <input value="'.$voir->statut.'" name="s" type="text">
                               <input value="'.$voir->statut.'" name="s" type="text">
                               <input value="'.$voir->lib_appart.'" name="a" type="text">
                               <input value="'.$voir->mont_reserv.'" name="m" type="text">
                               <input value="'.$voir->dat_arriv.'" name=datar type="text">
                               <input value="'.$voir->dat_dep.'" name="datdep" type="text">
                               <input value="'.$voir->objet.'" name="ob" type="text">
                               <input value="'.$voir->rem.'" name="rem" type="text">
                            </div>
                            <button class="btn btn-circle bg-gradient-info" type="submit"><font color="white"><i class="fa fa-print"></i></font>
                          </button>
                          </form>
                          
                          </div>
                          </td>
                          </tr>';
                        }  
                        @endphp
                  </tbody>

                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticy-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

   <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Voulez- vous vous déconnecter?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Cliquez sur "Deconnexion" si vous êtes prêt à quitter la session.</div>
        <div class="modal-footer">

          <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
           <form method="post" action="/login">{{ csrf_field() }}<button type="submit" class="btn btn-primary" name="logout">Déconnexion</button></form>
        </div>
      </div>
    </div>
  </div>


  <script language="javascript">
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
      coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.display === "block") {
          content.style.display = "none";
        } else {
          content.style.display = "block";
        }
      });
    } 
  </script>

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