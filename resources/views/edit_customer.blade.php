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

  <title>Modify - customer</title>

  <!-- Custom fonts for this template-->
  <link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

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
                <hr/>
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4">Modification de clients</h1>
                </div>
               
                <form class="user" method="post" action="/edit_customer">
                  {{ csrf_field() }}
                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <label>Titre:</label>
                      <select name="title" class="form-control" style="width: 100px">
                        <option>{{request('titre')}}</option>
                        <option>M</option>
                        <option>Mme</option>
                        <option>Mlle</option>
                      </select>
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input value="{{request('id')}}" name="id" style="display: none;">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      Nom:
                      <input type="text" name="nom" value="{{request('n')}}" class="form-control form-control-user" id="exampleFirstName" onkeyup="this.value=this.value.toUpperCase()">
                    </div>
                    <div class="col-sm-6">
                    Prénom(s):
                    <input type="text" name="pnom" value="{{request('p')}}"class="form-control form-control-user" id="exampleLastName" onkeyup="this.value=this.value.toUpperCase()">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      Téléphone:
                      <input type="text" value="{{request('tel')}}" class="form-control form-control-user" name="tel">
                    </div>
                    <div class="col-sm-6">
                    Adresse Email:
                    <input type="text" value="{{request('mail')}}"class="form-control form-control-user" name="mail">
                    </div>
                  </div>
                      

                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      Code postal:
                      <input type="text" value="{{request('address')}}" class="form-control form-control-user" name="ad" >
                    </div>
                    <div class="col-sm-6">
                   
                    </div>
                  </div>                

                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <button type="submit" name="mod" class="btn btn-primary">MODIFIER</button>
                    </div>
                    <div class="col-sm-6">
                      <button type="reset" name="reset" class="btn btn-danger">ANNULER</button>
                    </div>
                  </div>
                  <font color="green">@if(isset($message)){{$message}}@endif</font>
                </form>
              </div><hr>
              <div class="text-center">
                <a class="small" href="/customers"><font style="font-size: 20px">Retour en arrière</font></a>
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