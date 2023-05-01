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

$query = "SELECT * FROM `categories`;";
$result = mysqli_query($conn, $query);

$table = True;
$edit = False;
$complete = False;
$newcat = False;

if(isset($_POST['submit']) && !empty($_POST['select'])) {
    $edit = True;
    $table = False;
    $catedit = $_POST['select'];
}

if(isset($_POST['editsubmit'])) {
    $cat_id = $_POST['cat_id'];
    $newname = $_POST['newname'];

    if(isset($_POST['newpublished'])) {
        $newpub = 1;
    } else {
        $newpub = 0;
    }

    if($newpub != $_POST['ogpublished']) {
        $editquery = "UPDATE `categories` SET `published` = '$newpub' WHERE `categories`.`cat_id` = '$cat_id';";
        mysqli_query($conn, $editquery) or die("bad query");
    }
    if($newname != $_POST['ogname']) {
        $editquery = "UPDATE `categories` SET `cat_name` = '$newname' WHERE `categories`.`cat_id` = '$cat_id';";
        mysqli_query($conn, $editquery) or die("bad query");
    }
    $edit = False;
    $table = True;
    header('Refresh:0');
}

if(isset($_POST['newcat'])) {
    $newcat = True;
    $table = False;
    $edit = False;
}

if(isset($_POST['newcatmake'])) {
    $newcatname = $_POST['catname'];

    if(isset($_POST['published'])) {
        $published = 1;
    } else {
        $published = 0;
    }

    $newcatquery = "INSERT INTO `categories` (`cat_id`, `cat_name`, `published`) VALUES (NULL, '$newcatname', '$published');";
    mysqli_query($conn, $newcatquery) or die('bad insert q');


    $newcat = False;
    $table = True;
    header('Refresh:0');
}


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Yarn Corner Categories</title>

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
            <li class="nav-item active">
                <a class="nav-link" href="categorytable.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Categories</span></a>
            </li>
            <li class="nav-item">
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
                    <h1 class="h3 mb-2 text-gray-800" style='padding-top:20px;padding-bottom:20px'>Categories</h1>
                    <h2><?php if(isset($msg)) { echo $msg; } ?></h2>

                    <!-- DataTales Example -->
                    
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <?php if($table) { ?>
                                <div class="table-responsive">
                                    <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Category ID</th>
                                                <th>Name</th>
                                                <th>Published</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Select</th>
                                                <th>Category ID</th>
                                                <th>Name</th>
                                                <th>Published</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            
                                            <?php
                                            while($row = mysqli_fetch_array($result)) {
                                                $id = $row['cat_id'];
                                                ?>
                                                <tr>
                                                    <td><input type='radio' name='select' value='<?php echo $id; ?>'></td>
                                                    <td><?php echo $row['cat_id']; ?></td>
                                                    <td><?php echo $row['cat_name']; ?></td>
                                                    <td><?php 
                                                        $published = $row['published'];
                                                        if($published == 1) {
                                                            echo "Published";
                                                        } else {
                                                            echo "Not Published";
                                                        }
                                                    
                                                        ?>
                                                    </td>                                                    

                                            <?php } ?>
                                        </tbody>
                                    </table>
                                        <input type='submit' name='submit' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                    </form>
                                    <br>
                                        
                                        <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                            <input type='submit' name='newcat' value='New Category' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                        </form>
                                        
                                </div> <?php } elseif($edit) { 
                                    $catquery = "SELECT * FROM `categories` WHERE `cat_id`='$catedit'";
                                    $catresult = mysqli_query($conn, $catquery);
                                    while($row = mysqli_fetch_array($catresult)) {
                                        $cat_id = $row['cat_id'];
                                        $name = $row['cat_name'];
                                        $published = $row['published'];
                                    }
                                    ?>
                                    <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div><h3>Edit Category</h3><br>
                                    <h4>Published: <label class='switch'><input type='checkbox' name='newpublished' <?php if($published == 1) { echo "checked"; } else { echo "unchecked"; } ?>><span class='slider round'></span></label></h4>
                                    <br>

                                    <h4>Name: <input type='text' name='newname' value='<?php echo $name; ?>'></h4><br>

                                    

                                    
                                    <!-- hidden input to check for edited fields -->
                                    <input type='text' name='cat_id' value='<?php echo $cat_id; ?>' readonly hidden>
                                    <input type='text' name='ogname' value='<?php echo $name; ?>' readonly hidden>
                                    <input type='text' name='ogpublished' value='<?php echo $published; ?>' readonly hidden>
                                    
                                    <input type='submit' name='editsubmit' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                    <button value="Cancel" style="background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px" onclick="window.location.href='categorytable.php'">Cancel</button>
                                    
                                    </form>
                                
                                <?php } elseif($newcat) { ?>

                                    <h3>New Category</h3><br>
                                    <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <h4>Name: <input type='text' name='catname'></h4><br>
                                        <h4>Published: <label class='switch'><input type='checkbox' name='published' checked><span class='slider round'></span></label></h4>
                                        <input type='submit' name='newcatmake' style="background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px">
                                        <button value="Cancel" style="background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px" onclick="window.location.href='categorytable.php'">Cancel</button>
                                    </form>
                                    
                                    
                                    <?php
                                } ?>
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