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
if (ISSET($_POST['loginsubmit'])) {

    //connect & run query
    $query = "SELECT * FROM `users` WHERE username = '" . $_POST['username'] . "'";
    $result = mysqli_query($conn, $query) or die($msg = 'That username does not exist');
    $auth = True;

    if(mysqli_fetch_array($result) == '') {
        $auth = False;
        $msg = 'That username does not exist';
    }
    $result = mysqli_query($conn, $query) or die($msg = 'That username does not exist');
    // grab alllllll the information
    while ($row = mysqli_fetch_array($result)) {
        
        //profile info
        $id = $row['user_id'];
        $level = $row['level_id'];
        $username = $row['username'];
        $password = $row['pwd'];
        $email = $row['email'];
        $fname = $row['fname'];
        $lname = $row['lname'];
        $pfp = $row['pfp'];
        $enabled = $row['enabled'];
        if($enabled == 0) {
          $auth = False;
          $msg = "That user has been disabled.";
        }

    }

    //if they're good, bring allllll that information into their session
    if($auth) {
        if (password_verify($_POST['password'], $password)) {
            //profile info
            $_SESSION['id'] = $id;
            $_SESSION['level'] = $level;
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['email'] = $email;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['pfp'] = $pfp;
            $_SESSION['dob'] = $dob;
            // send em
            header('location: index.php');
        } elseif (!password_verify($_POST['password'], $password)) {
            $msg = 'You did not enter the correct password';
        }

    } 
    
}

if(isset($_POST['cancel'])) {
  header('location:index.php');
}
?>

<!doctype html>
<html lang="en">
  <title>Yarn Corner Login</title>
  <head>
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
              <h3>Log In</h3>
              <p class="mb-4"><?php if(isset($msg)) { echo $msg; } ?></p>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <div class="form-group first">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name='username' autofocus>

              </div>
              <div class="form-group last mb-4">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name='password'>
                
              </div>
              
              <div class="d-flex mb-5 align-items-center">
                
              </div>

              <input type="submit" value="Log In" name='loginsubmit' class="btn btn-block btn-primary">
              
            </form>
            <br><br>
            <h6 style='text-align:center'><a href='register.php'>Don't have an account?</a></h6><br>
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