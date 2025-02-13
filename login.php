<?php  
session_start();
include('connect.php');

if(isset($_POST['btnLogin'])) 
{
    $txtEmail = $_POST['txtEmail'];
    $txtPassword = $_POST['txtPassword'];

    $checkAccount = "SELECT * FROM employee WHERE Email='$txtEmail' AND Password='$txtPassword'";
    $ret=mysqli_query($connection,$checkAccount);
    $size=mysqli_num_rows($ret);
    $arr=mysqli_fetch_array($ret);
  
    if($size < 1) 
    {
      if (isset($_SESSION['logincount'])) {
        $countError = $_SESSION['logincount'];

        if ($countError == 1) {
            echo "<script>window.alert('Login Failed. Attempt 2 of 3.')</script>";
            $_SESSION['logincount'] = 2;
        } elseif ($countError == 2) {
            echo "<script>window.alert('Login Failed. Attempt 3 of 3.')</script>";
            echo "<script>window.location='WaitingRoom.php'</script>";
        }
    } else {
        echo "<script>window.alert('Login Failed. Attempt 1 of 3.')</script>";
        $_SESSION['logincount'] = 1;
    }

}
    else
    { 
      $_SESSION['EmployeeID']=$arr['EmployeeID'];
      $_SESSION['Username']=$arr['Username'];
      $_SESSION['Position']=$arr['Position'];
      $_SESSION['ProfilePicture']=$arr['ProfilePicture'];
  
      echo "<script>window.alert('Successfully Login!')</script>";
      echo "<script>window.location='index.php'</script>";
    }
  }
  ?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Aung Bi Win Rice Trading House - Login</title>
    <link rel="shortcut icon" href="img/grain.svg" type="image/svg+xml" />
    <!-- Custom fonts for this template-->
    <link
      href="vendor/fontawesome-free/css/all.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet"
    />

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
  </head>

  <body class="bg-gradient-primary">
    <div class="container">
      <!-- Outer Row -->
      <div class="row justify-content-center">
        <div class="col-xl-5 ">
          <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
     
                <div class="">
                  <div class="p-5">
                    <div class="text-center">
                      <img src="img/grain.svg" alt="Logo" class="mb-4" style="width: 100px; height: auto;">
                      <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                    </div>
                    <form class="user" action="login.php" method="POST">
                      <div class="form-group">
                        <input
                          type="email"
                          class="form-control form-control-user"           
                          name="txtEmail"
                          placeholder="Enter Email Address..."
                          required
                        />
                      </div>
                      <div class="form-group">
                        <input
                          type="password"
                          class="form-control form-control-user"
                 
                          name="txtPassword"
                          placeholder="Password"
                          required
                        />
                      </div>
                      <div class="form-group">
                        <div class="custom-control custom-checkbox small">
                          <input
                            type="checkbox"
                            class="custom-control-input"
                            id="customCheck"
                          />
                          <label class="custom-control-label" for="customCheck"
                            >Remember Me</label
                          >
                        </div>
                      </div>
                      <div class="form-group">
                      <div class="g-recaptcha " data-sitekey="6LdaM_UpAAAAAGS7yrVVfZcCyfl3JLK0yKJp3K9_">
                      </div>
                      <script src="https://www.google.com/recaptcha/api.js" async defer></script>

                      </div>

                      <button type="submit" class="btn btn-primary btn-user btn-block" name="btnLogin">
                        Login
                      </button>
                      <hr />
                    </form>
                  </div>
                </div>
        
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
  </body>
</html>
