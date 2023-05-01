<?php

/*

Hello! Just as a quick note, this code is pretty poorly documented, that's something
I'm aware of and I'm working on updating it in my free time!
Just know that documentation in my more recent programs is much more thorough and clean!

*/


session_start();

if(!isset($_SESSION['level']) OR $_SESSION['level'] == 1) {
    header('location:index.php');
}


require('required/conn.php');

$query = "SELECT `users`.`user_id`, `level`.`level_name`, 
        `users`.`username`, `users`.`fname`, `users`.`lname`, 
        `users`.`email`, `users`.`enabled`, `users`.`bio`,
        `users`.`instagram`, `users`.`twitter`, `users`.`youtube`,
        `users`.`etsy`
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


if(isset($_POST['editsubmit'])) {
    $user_id = $_POST['user_id'];
    $newusername = mysqli_escape_string($conn, $_POST['newusername']);
    $newlevel = $_POST['newlevel'];
    $newfname = mysqli_escape_string($conn, $_POST['newfname']);
    $newlname = mysqli_escape_string($conn, $_POST['newlname']);
    $newemail = mysqli_escape_string($conn, $_POST['newemail']);
    $newpwd = $_POST['pwd'];
    $newbio = mysqli_escape_string($conn, $_POST['newbio']);

    $newinstagram = mysqli_escape_string($conn, $_POST['instagram']);
	$newtwitter = mysqli_escape_string($conn, $_POST['twitter']);
	$newyoutube = mysqli_escape_string($conn, $_POST['youtube']);
	$newetsy = mysqli_escape_string($conn, $_POST['etsy']);

    if(isset($_POST['newenabled'])) {
        $newenabled = 1;
    } else {
        $newenabled = 0;
    }

    if($newenabled != $_POST['ogenabled']) {
        $editquery = "UPDATE `users` SET `enabled` = '$newenabled' WHERE `users`.`user_id` = '$user_id';";
        mysqli_query($conn, $editquery) or die("bad enabled query");
    }
    
    $editquery = "UPDATE `users` SET `username` = '$newusername' WHERE `users`.`user_id` = '$user_id';";
    mysqli_query($conn, $editquery) or die("bad username query");

    $editquery = "UPDATE `users` SET 
                `instagram` = '$newinstagram', 
                `twitter` = '$newtwitter', 
                `youtube` = '$newyoutube', 
                `etsy` = '$newetsy' 
                WHERE `users`.`user_id` = '$user_id';";
    mysqli_query($conn, $editquery) or die($error = "bad social media query");

    $editquery = "UPDATE `users` SET `bio` = '$newbio' WHERE `users`.`user_id` = '$user_id';";
    mysqli_query($conn, $editquery) or die("bad bio query");
    
 
    $editquery = "UPDATE `users` SET `level_id` = '$newlevel' WHERE `users`.`user_id` = '$user_id';";
    mysqli_query($conn, $editquery) or die("bad base level query");

    
    
    
    if($newfname != $_POST['ogfname']) {
        $editquery = "UPDATE `users` SET `fname` = '$newfname' WHERE `users`.`user_id` = '$user_id';";
        mysqli_query($conn, $editquery) or die("bad fname query");
    }
    if($newlname != $_POST['oglname']) {
        $editquery = "UPDATE `users` SET `lname` = '$newlname' WHERE `users`.`user_id` = '$user_id';";
        mysqli_query($conn, $editquery) or die("bad lname query");
    }
    if($newemail != $_POST['ogemail']) {
        $editquery = "UPDATE `users` SET `email` = '$newemail' WHERE `users`.`user_id` = '$user_id';";
        mysqli_query($conn, $editquery) or die("bad email query");
    }
    if($newpwd != '') {
        $pwdhash = password_hash($newpwd, PASSWORD_DEFAULT);
        $editquery = "UPDATE `users` SET `pwd` = '$pwdhash' WHERE `users`.`user_id` = '$user_id';";
        mysqli_query($conn, $editquery) or die("bad pwd query");
    }

    $edit = False;
    $table = True;
    header('Refresh:0');
}


if(isset($_POST['newuser']))  {
    header('location:adminmakeuser.php');
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
            </li><br>

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
                                <?php if($table) { ?>
                                <div class="table-responsive">
                                    <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <table class="table table-bordered" id="dataTable" width="200%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>User ID</th>
                                                <th>Username</th>
                                                <th>Level</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Enabled</th>
                                                <th>Bio</th>
                                                <th>Instagram</th>
                                                <th>Twitter</th>
                                                <th>Youtube</th>
                                                <th>Etsy</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Select</th>
                                                <th>User ID</th>
                                                <th>Username</th>
                                                <th>Level</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Enabled</th>
                                                <th>Bio</th>
                                                <th>Instagram</th>
                                                <th>Twitter</th>
                                                <th>Youtube</th>
                                                <th>Etsy</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            
                                            <?php
                                            while($row = mysqli_fetch_array($result)) {
                                                $id = $row['user_id'];
                                                ?>
                                                <tr>
                                                    <td><input type='radio' name='select' value='<?php echo $id; ?>'></td>
                                                    <td><?php echo $row['user_id']; ?></td>
                                                    <td><?php echo $row['username']; ?></td>
                                                    <td><?php echo $row['level_name']; ?></td>
                                                    <td><?php echo $row['fname']; ?></td>
                                                    <td><?php echo $row['lname']; ?></td>
                                                    <td><?php echo $row['email']; ?></td>
                                                    <td><?php 
                                                        $published = $row['enabled'];
                                                        if($published == 1) {
                                                            echo "Enabled";
                                                        } else {
                                                            echo "Disabled";
                                                        }
                                                    
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        $bio = $row['bio'];
                                                        $biowrds = explode(' ', $bio);
                                                        if(count($biowrds) < 10) {
                                                            echo $bio;
                                                        } else {
                                                            for($i = 0; $i < 10; $i++) {
                                                                echo $biowrds[$i] . " ";
                                                                if($i == 9) {
                                                                    echo "...";
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><a href="<?php echo $row['instagram']; ?>" target="_blank"><?php echo $row['instagram']; ?></a></td>
                                                    <td><a href="<?php echo $row['twitter']; ?>" target="_blank"><?php echo $row['twitter']; ?></a></td>
                                                    <td><a href="<?php echo $row['youtube']; ?>" target="_blank"><?php echo $row['youtube']; ?></a></td>
                                                    <td><a href="<?php echo $row['etsy']; ?>" target="_blank"><?php echo $row['etsy']; ?></a></td>
                                                    

                                            <?php } ?>
                                        </tbody>
                                    </table>
                                                <input type='submit' name='submit' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                                
                                            </form><br>
                                            <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                <input type='submit' name='newuser' value='New User' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                            </form>

                                </div> <?php } elseif($edit) { 
                                    $userquery = "SELECT `users`.`user_id`, `level`.`level_name`, 
                                                `users`.`username`, `users`.`fname`, `users`.`lname`, 
                                                `users`.`email`, `users`.`enabled`, `users`.`bio`,
                                                `users`.`instagram`, `users`.`twitter`, `users`.`youtube`,
                                                `users`.`etsy`
                                                FROM `users`
                                                INNER JOIN `level` ON `level`.`level_id`=`users`.`level_id`
                                                WHERE `users`.`user_id`='$useredit'";
                                    $userresult = mysqli_query($conn, $userquery);
                                    while($row = mysqli_fetch_array($userresult)) {
                                        $user_id = $row['user_id'];
                                        $username = $row['username'];
                                        $levelname = $row['level_name'];
                                        $fname = $row['fname'];
                                        $lname = $row['lname'];
                                        $email = $row['email'];
                                        $enabled = $row['enabled'];
                                        $bio = $row['bio'];
                                        $instagram = $row['instagram'];
                                        $twitter = $row['twitter'];
                                        $youtube = $row['youtube'];
                                        $etsy = $row['etsy'];
                                    }
                                    ?>
                                    <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div><h3>Edit User</h3><br>
                                    <h5>Enabled: <label class='switch'><input type='checkbox' name='newenabled' <?php if($enabled == 1) { echo "checked"; } else { echo "unchecked"; } ?>><span class='slider round'></span></label></h5>
                                    <br>
                                    <table>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Username: </h5></td>
                                            <td><h5><input type='text' name='newusername' value='<?php echo $username; ?>'></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Level: </h5></td>
                                            <td><select id='newlevel' name='newlevel'>
                                                <?php while($row = mysqli_fetch_array($levelresult)) {
                                                    $level_id = $row['level_id'];
                                                    $level_name = $row['level_name'];
                                                    if($levelname == $level_name) {
                                                        echo "<h5><option value='$level_id' selected>$level_name</option></h5>";
                                                    } else {
                                                        echo "<h5><option value='$level_id'>$level_name</option></h5>";
                                                    }
                                                    
                                                } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:10px'><h5>First Name: </h5></td>
                                            <td><h5><input type='text' name='newfname' value='<?php echo $fname; ?>'></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Last Name: </h5></td>
                                            <td><h5><input type='text' name='newlname' value='<?php echo $lname; ?>'></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Email: </h5></td>
                                            <td><h5><input type='text' name='newemail' value='<?php echo $email; ?>'></h5></td>
                                        <tr>
                                            <td style='padding-right:10px'><h5>New Password: </h5></td>
                                            <td><h5><input type='text' name='pwd'></h5></td>
                                        </tr>
                                    </table>
                                    <table>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Instagram: </h5></td>
                                            <td><h5><input type='url' name='instagram' value='<?php echo $instagram; ?>'></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Twitter: </h5></td>
                                            <td><h5><input type='url' name='twitter' value='<?php echo $twitter; ?>'></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Youtube: </h5></td>
                                            <td><h5><input type='url' name='youtube' value='<?php echo $youtube; ?>'></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:10px'><h5>Etsy: </h5></td>
                                            <td><h5><input type='url' name='etsy' value='<?php echo $etsy; ?>'></h5></td>
                                        </tr>

                                    </table>
                                    <textarea name="newbio" id="editor" width='50%' placeholder='Enter bio here...'>
                                        <?php if(isset($bio)) { echo $bio; } ?>
                                    </textarea><br>

                                    <script>
                                        ClassicEditor
                                            .create( document.querySelector( '#editor' ) )
                                            .catch( error => {
                                                console.error( error );
                                            } );
                                    </script>
                                    <br>
                                    

                                    
                                    <!-- hidden input to check for edited fields -->
                                    <input type='text' name='user_id' value='<?php echo $user_id; ?>' readonly hidden>
                                    <input type='text' name='ogenabled' value='<?php echo $enabled; ?>' readonly hidden>
                                    <input type='text' name='ogusername' value='<?php echo $username; ?>' readonly hidden>
                                    <input type='text' name='oglevel' value='<?php echo $levelname; ?>' readonly hidden>
                                    <input type='text' name='ogfname' value='<?php echo $fname; ?>' readonly hidden>
                                    <input type='text' name='oglname' value='<?php echo $lname; ?>' readonly hidden>
                                    <input type='text' name='ogemail' value='<?php echo $email; ?>' readonly hidden>
                                    
                                    <input type='submit' name='editsubmit' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                    <button value="Cancel" style="background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px" onclick="window.location.href='usertable.php'">Cancel</button>
                                    </form>
                                
                                <?php } ?>

                            </div>
                    </div> 

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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