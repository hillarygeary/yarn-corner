<?php

/*

Hello! Just as a quick note, this code is pretty poorly documented, that's something
I'm aware of and I'm working on updating it in my free time!
Just know that documentation in my more recent programs is much more thorough and clean!

*/


session_start();

require('pages.php');
$conn = mysqli_connect('localhost', 'root', '', 'geary') or die('bad db conn');

$page = 'Log In';



// LOGIN VERIFICATION
if (ISSET($_POST['registersubmit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $error = False;

    if(!empty($username) && !empty($email) && !empty($lname) && !empty($fname) && !empty($password)) {
        
        if (strlen($password) >= 5 && strlen($username) >= 4) {
            $query = "SELECT username FROM `users`;";
            $result = mysqli_query($conn, $query) or DIE('bad query');
            $create = TRUE;
            while ($row = mysqli_fetch_array($result)) {
                $dbuser = $row['username'];
                if ($dbuser == $username) {
                    $error = True;
                }
            }
            if ($error) {
                $msg = 'That username is already taken';
            } else {
                $pwdhash = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO `users` (`user_id`, `level_id`, `username`, `pwd`, `email`, `fname`, `lname`, `pfp`, `enabled`, `bio`) VALUES (NULL, '1', '$username', '$pwdhash', '$email', '$fname', '$lname', '', '1', '');";
                mysqli_query($conn, $query) or DIE('bad query');
                header('location: login.php');
            }
            
        } elseif (strlen($pwd) < 6) {
            $msg = 'Password must be at least 6 characters.';
        } elseif (strlen($username) < 4) {
            $msg = 'Username must be at least 4 characters.';
        }
    } else {
        $msg = "Please fill in all fields";
    }

    
}

if(isset($_POST['cancel'])) {
  header('location:index.php');
}
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Yarn Corner Register</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/owl.carousellogin.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstraplogin.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="css/stylelogin.css">

    <style>
        .contain {
            height: 100%;
            position: relative;
        }

        .vertical-center {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }
        .titlebutton {
          background-color: transparent;
          background-repeat: no-repeat;
          border: none;
          cursor: pointer;
          overflow: hidden;
          outline: none;
        }
    </style>

  </head>
  <body>

  <div style='float:right;padding:20px'>  
    <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type='submit' name='cancel' class="titlebutton" value='X'>
    </form>
  </div>
  
  <div class="content">
    <div class="container vertical-center">
      <div class="row">
        <div class="col-md-6">
          <img src="images/background.png" alt="Image" class="img-fluid">
        </div>
        <div class="col-md-6 contents">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="mb-4">
              <h3>Register</h3>
              <p class="mb-4"><?php if(isset($msg)) { echo $msg; } ?></p>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <div class="form-group first">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name='email'>

              </div>
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name='username' minlength='4' maxlength='20' autofocus>

              </div>
              <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" class="form-control" id="fname" name='fname'>

              </div>
              <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control" id="lname" name='lname'>

              </div>
              <div class="form-group last mb-4">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name='password'minlength='5'>
                
              </div>
              
              <div class="d-flex mb-5 align-items-center">
                
              </div>

              <input type="submit" value="Submit" name='registersubmit' class="btn btn-block btn-primary">
              
            </form>
            <br><br>
            <h6 style='text-align:center'><a href='login.php'>Have an account already?</a></h6><br>
            <h6 style='text-align:center'><a href='index.php'>Back to Yarn Corner</a></h6>
            </div>
          </div>
          
        </div>
        
      </div>
    </div>
  </div>

  
    <script src="js/jquery-3.3.1login.min.js"></script>
    <script src="js/popperlogin.min.js"></script>
    <script src="js/bootstraplogin.min.js"></script>
    <script src="js/mainlogin.js"></script>
  </body>
</html>