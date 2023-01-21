@php
  if(isset($_GET['logout']))
  session_destroy();
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


        <title>Admin | Login</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
         <!-- Custom fonts for this template-->
      <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
      <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">

       
    </head>


    <body style="background-image: url('images/annekam2.jpg'); background-repeat: inherit;">
  
         <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
           
          <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5"  style="background-color: #d6cdcd">
              <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                  <div class="col-lg-12 d-none d-lg-block bg-login-image"></div>
                  <div class="col-lg-6" style="background-color: #d6cdcd">
                    <div class="p-5">
                      <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Bienvenue!</h1>
                      </div>
                      <form class="user" method="post" action="/login">
                        {{ csrf_field() }}
                        <div class="form-group">
                          <input type="text" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Entrer le pseudo..."  name="pseudo" >
                          @if($errors->has('pseudo'))
                            <p style="color: red">{{ $errors->first('pseudo') }}</p>
                         @endif
                        </div>
                        <div class="form-group">
                          <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Mot de Passe" name="password" >
                          @if($errors->has('password'))
                            <p style="color: red">{{ $errors->first('password') }}</p>
                         @endif
                        </div>
                        <input name="go" type="submit" class="btn btn-primary" value="connexion">
                         
                        <hr>
                       
                      </form>
                      <hr>
                        @if(isset($fail))
                            <p style="color: red">{{ $fail }}</p>
                         @endif
                      <div class="text-center">
                        <font color="black">Mot de passe oublié?</font>
                      </div>
                      <div class="text-center">
                        <!--<a class="small" href="admin/add_admin.php">Créer un compte admin!</a>-->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


          </div>

        </div>

    </div>

  <!-- Bootstrap core JavaScript-->
  <script src="jquery/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

    </body>s
    
</html>
