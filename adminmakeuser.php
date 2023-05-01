<?php
session_start();

if(!isset($_SESSION['level']) OR $_SESSION['level'] == 1) {
    header('location:index.php');
}


require('required/conn.php');
// $conn = mysqli_connect('localhost', 'root', '', 'geary') or die('bad db conn');

$query = "SELECT `users`.`user_id`, `level`.`level_name`, 
        `users`.`username`, `users`.`fname`, `users`.`lname`, 
        `users`.`email`, `users`.`enabled`
        FROM `users`
        INNER JOIN `level` ON `level`.`level_id`=`users`.`level_id`";
$result = mysqli_query($conn, $query);

$levelquery = "SELECT * FROM `level`";
$levelresult = mysqli_query($conn, $levelquery);

$table = True;
$edit = False;
$complete = False;

if(isset($_POST['submit']) && !empty($_POST['select'])) {
    $edit = True;
    $table = False;
    $useredit = $_POST['select'];
}

// if(isset($_POST['editsubmit'])) {
//     $user_id = $_POST['user_id'];
//     $newusername = $_POST['newusername'];
//     $newlevel = $_POST['newlevel'];
//     $newfname = $_POST['newfname'];
//     $newlname = $_POST['newlname'];
//     $newemail = $_POST['newemail'];
//     $newpwd = $_POST['pwd'];

//     if(isset($_POST['newenabled'])) {
//         $newenabled = 1;
//     } else {
//         $newenabled = 0;
//     }

//     if($newenabled != $_POST['ogenabled']) {
//         $editquery = "UPDATE `users` SET `enabled` = '$newenabled' WHERE `users`.`user_id` = '$user_id';";
//         mysqli_query($conn, $editquery) or die("bad enabled query");
//     }
//     // if($newusername != $_POST['newusername']) {
//         $editquery = "UPDATE `users` SET `username` = '$newusername' WHERE `users`.`user_id` = '$user_id';";
//         mysqli_query($conn, $editquery) or die("bad username query");
//     // }
    
 
//     $editquery = "UPDATE `users` SET `level_id` = '$newlevel' WHERE `users`.`user_id` = '$user_id';";
//     mysqli_query($conn, $editquery) or die("bad base level query");

    
    
    
//     if($newfname != $_POST['ogfname']) {
//         $editquery = "UPDATE `users` SET `fname` = '$newfname' WHERE `users`.`user_id` = '$user_id';";
//         mysqli_query($conn, $editquery) or die("bad fname query");
//     }
//     if($newlname != $_POST['oglname']) {
//         $editquery = "UPDATE `users` SET `lname` = '$newlname' WHERE `users`.`user_id` = '$user_id';";
//         mysqli_query($conn, $editquery) or die("bad lname query");
//     }
//     if($newemail != $_POST['ogemail']) {
//         $editquery = "UPDATE `users` SET `email` = '$newemail' WHERE `users`.`user_id` = '$user_id';";
//         mysqli_query($conn, $editquery) or die("bad email query");
//     }
//     if($newpwd != '') {
//         $pwdhash = password_hash($newpwd, PASSWORD_DEFAULT);
//         $editquery = "UPDATE `users` SET `pwd` = '$pwdhash' WHERE `users`.`user_id` = '$user_id';";
//         mysqli_query($conn, $editquery) or die("bad pwd query");
//     }

//     $edit = False;
//     $table = True;
//     header('Refresh:0');
// }



if (ISSET($_POST['registersubmit'])) {
    
    if(isset($_POST['published'])) {
        $published = 1;
    } else {
        $published = 0;
    }
    $username = mysqli_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $email = mysqli_escape_string($conn, $_POST['email']);
    $fname = mysqli_escape_string($conn, $_POST['fname']);
    $lname = mysqli_escape_string($conn, $_POST['lname']);
    $error = False;
    $userlevel = $_POST['levelselect'];
    $bio = mysqli_escape_string($conn, $_POST['bio']);
    $instagram = mysqli_escape_string($conn, $_POST['instagram']);
    $twitter = mysqli_escape_string($conn, $_POST['twitter']);
    $youtube = mysqli_escape_string($conn, $_POST['youtube']);
    $etsy = mysqli_escape_string($conn, $_POST['etsy']);
    // } elseif( ) {}

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
                // $query = "INSERT INTO `users` (`user_id`, `level_id`, `username`, `pwd`, `email`, `fname`, `lname`, `pfp`, `enabled`, `dob`) VALUES (NULL, '1', '$username', '$pwdhash', '$email', '$fname', '$lname', '', '1', '');";
                $query = "INSERT INTO `users` (`user_id`, `level_id`, `username`, `pwd`, `email`, `fname`, `lname`, `pfp`, `enabled`, `bio`, `instagram`, `twitter`, `youtube`, `etsy`) 
                VALUES (NULL, '1', '$username', '$pwdhash', '$email', '$fname', '$lname', '', '1', '$bio', '$instagram', '$twitter', '$youtube', '$etsy');";
                mysqli_query($conn, $query) or DIE('bad query');
                header('location: usertable.php');
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
  header('location:usertable.php');
}

if(isset($_POST['newuser']))  {
    $table = False;
    $newuser = True;
    $edit = false;
}





?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Yarn Corner Users</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #4e73df;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #4e73df;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3">Yarn Corner</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Admin Tables
            </div>



            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="posttable.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Posts</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="categorytable.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Categories</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="usertable.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Users</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="commenttable.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Comments</span></a>
            </li>

            
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Log Out</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid" > 

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800" style='padding-top:20px;padding-bottom:20px'>Users</h1>

                    <!-- DataTales Example -->
                    
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                
                                    <h4>Enabled: <label class='switch'><input type='checkbox' name='newpublished' checked><span class='slider round'></span></label></h4>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <div class="form-group first">
                                      <label for="email">Email</label>
                                      <input type="email" class="form-control" id="email" name='email' required>
                      
                                    </div>
                                    <div class="form-group">
                                      <label for="username">Username</label>
                                      <input type="text" class="form-control" id="username" name='username' minlength='4' maxlength='20' required>
                      
                                    </div>
                                    <div class="form-group">
                                      <label for="fname">First Name</label>
                                      <input type="text" class="form-control" id="fname" name='fname' required>
                      
                                    </div>
                                    <div class="form-group">
                                      <label for="lname">Last Name</label>
                                      <input type="text" class="form-control" id="lname" name='lname' required>
                      
                                    </div>
                                    <!-- <table>
                                    <tr>
                                            <td style='padding-right:10px'>Level: </h5>
                                            <td> -->
                                                <!-- <h5><input list='levelselect' name='newlevel' placeholder='Select' class='form-group' required> -->
                                            <label for='levelselect'>Level</label>
                                            <select id='levelselect' name='levelselect' class='form-control'>
                                                <?php while($row = mysqli_fetch_array($levelresult)) {
                                                    $level_id = $row['level_id'];
                                                    $level_name = $row['level_name'];
                                                    echo "<option value='$level_id'>$level_name</option>";
                                                } ?>
                                </select>
                                    </tr>
                                    </table>
                                    <div class="form-group last mb-4">
                                      <label for="password">Password</label>
                                      <input type="password" class="form-control" id="password" name='password'minlength='5'>
                                      
                                    </div>
                                    <table>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Instagram: </h5></td>
                                            <td><h5><input type='url' name='instagram'></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Twitter: </h5></td>
                                            <td><h5><input type='url' name='twitter'></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Youtube: </h5></td>
                                            <td><h5><input type='url' name='youtube'></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Etsy: </h5></td>
                                            <td><h5><input type='url' name='etsy'></h5></td>
                                        </tr>

                                    </table>
                                    <textarea name="bio" id="editor" width='50%' placeholder='Enter bio here...'>
                                    </textarea><br>

                                    <script>
                                        ClassicEditor
                                            .create( document.querySelector( '#editor' ) )
                                            .catch( error => {
                                                console.error( error );
                                            } );
                                    </script>
                                    
                                    <div class="d-flex mb-5 align-items-center">
                                      
                                    </div>
                      
                                    <input type="submit" value="Submit" name='registersubmit' class="btn  btn-primary">
                                    <button value="Cancel" class="btn btn-primary" onclick="window.location.href='usertable.php'">Cancel</button>
                                    
                                    
                                    
                                    
                                    
                            </div>
                    </div> 

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>