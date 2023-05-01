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

$query = "SELECT `comments`.`comment_id`, `users`.`username`, 
        `posts`.`title`, `comments`.`timestamp`, 
        `comments`.`content`, `comments`.`published`
        FROM `comments`
        INNER JOIN `users` ON `users`.`user_id`=`comments`.`user_id`
        INNER JOIN `posts` ON `posts`.`post_id`=`comments`.`post_id`";
$result = mysqli_query($conn, $query);

$table = True;
$edit = False;
$complete = False;

if(isset($_POST['submit']) && !empty($_POST['select'])) {
    $edit = True;
    $table = False;
    $commentedit = $_POST['select'];
}

if(isset($_POST['editsubmit'])) {
    $comment_id = $_POST['comment_id'];
    $commentcontent = mysqli_escape_string($conn, $_POST['commedit']);

    if(isset($_POST['newpublished'])) {
        $newpub = 1;
    } else {
        $newpub = 0;
    }

    $editquery = "UPDATE `comments` SET `published` = '$newpub', `content`='$commentcontent' WHERE `comments`.`comment_id` = '$comment_id';";
    mysqli_query($conn, $editquery) or die("bad query");

    $edit = False;
    $table = True;
    header('Refresh:0');
}

if(isset($_POST['newcommmake'])) {

    $commuser_id = $_SESSION['id'];
    $newcommcontent = mysqli_escape_string($conn, $_POST['commcontent']);
    $commpost_id = $_POST['postname'];

    if(isset($_POST['published'])) {
        $published = 1;
    } else {
        $published = 0;
    }

    $newcommquery = "INSERT INTO `comments` (`comment_id`, `user_id`, `post_id`, `timestamp`, `commenttitle`, `content`, `published`) VALUES (NULL, '$commuser_id', '$commpost_id', current_timestamp(), '', '$newcommcontent', '$published');";
    
    mysqli_query($conn, $newcommquery) or die('bad insert q');


    $newcomm = False;
    $table = True;
    header('Refresh:0');
}

if(isset($_POST['newcomm'])) {
    $newcomm = True;
    $table = False;
    $edit = False;
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

    <title>Yarn Corner Comments</title>

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
            <li class="nav-item">
                <a class="nav-link" href="usertable.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Users</span></a>
            </li>
            <li class="nav-item active">
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
                    <h1 class="h3 mb-2 text-gray-800" style='padding-top:20px;padding-bottom:20px'>Comments</h1>
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
                                                <th>Comment ID</th>
                                                <th>User</th>
                                                <th>Post</th>
                                                <th>Timestamp</th>
                                                <th>Comment</th>
                                                <th>Published</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                            <th>Select</th>
                                                <th>Comment ID</th>
                                                <th>User</th>
                                                <th>Post</th>
                                                <th>Timestamp</th>
                                                <th>Comment</th>
                                                <th>Published</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            
                                            <?php
                                            while($row = mysqli_fetch_array($result)) {
                                                $id = $row['comment_id'];
                                                ?>
                                                <tr>
                                                    <td><input type='radio' name='select' value='<?php echo $id; ?>'></td>
                                                    <td><?php echo $row['comment_id']; ?></td>
                                                    <td><?php echo $row['username']; ?></td>
                                                    <td><?php echo $row['title']; ?></td>
                                                    <td><?php echo $row['timestamp']; ?></td>
                                                    <td><?php echo $row['content']; ?></td>
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
                                            </form><br>
                                            <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                <input type='submit' name='newcomm' value='New Comment' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                            </form>

                                </div> <?php } elseif($edit) { 
                                    $commentquery = "SELECT `comments`.`comment_id`, `users`.`username`, 
                                                    `posts`.`title`, `comments`.`timestamp`, 
                                                    `comments`.`content`, `comments`.`published`
                                                    FROM `comments`
                                                    INNER JOIN `users` ON `users`.`user_id`=`comments`.`user_id`
                                                    INNER JOIN `posts` ON `posts`.`post_id`=`comments`.`post_id` 
                                                    WHERE `comment_id`='$commentedit'";
                                    $commentresult = mysqli_query($conn, $commentquery) or die('bad comment query');
                                    while($row = mysqli_fetch_array($commentresult)) {
                                        $comment_id = $row['comment_id'];
                                        $username = $row['username'];
                                        $title = $row['title'];
                                        $timestamp = $row['timestamp'];
                                        $content = $row['content'];
                                        $published = $row['published'];
                                    }
                                    ?>
                                    <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div><h3>Edit User</h3><br>
                                    <h5>Published:     <label class='switch'><input type='checkbox' name='newpublished' <?php if($published == 1) { echo "checked"; } else { echo "unchecked"; } ?>><span class='slider round'></span></label></h5>
                                    <br><br>
                                    <table>
                                        <tr>
                                            <td style='padding-right:15px'><h5>Username: </h5></td>
                                            <td><h5><?php echo $username; ?></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:15px'><h5>Post: </h5></td>
                                            <td><h5><?php echo $title; ?></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:15px'><h5>Timestamp </h5></td>
                                            <td><h5><?php echo $timestamp; ?></h5></td>
                                        </tr>
                                        <tr>
                                            <td style='padding-right:15px'><h5>Comment </h5></td>
                                            <td><textarea name="commedit" id="message" cols="60" rows="10" class="form-control" maxlength='500' required><?php echo $content; ?></textarea></td>
                                        </tr>
                                    </table>
                                    <br>

                                    

                                    
                                    <!-- hidden input to check for edited fields -->
                                    <input type='text' name='comment_id' value='<?php echo $comment_id; ?>' readonly hidden>
                                    <input type='text' name='ogname' value='<?php echo $name; ?>' readonly hidden>
                                    <input type='text' name='ogpublished' value='<?php echo $published; ?>' readonly hidden>
                                    
                                    <input type='submit' name='editsubmit' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                    <button value="Cancel" style="background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px" onclick="window.location.href='commenttable.php'">Cancel</button>
                                    </form>
                                
                                <?php } elseif($newcomm) { 
                                    
                                    ?>

                                    <h3>New Comment</h3><br>
                                    <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <h4>Published: <label class='switch'><input type='checkbox' name='published' checked><span class='slider round'></span></label></h4>                                    
                                        <h4>Content: <textarea name="commcontent" id="message" cols="60" rows="10" class="form-control" maxlength='500' required></textarea></h4>
                                        <table>
                                            <tr>
                                                <td style='padding-right:10px'><h5>Post: </h5></td>
                                                <td>
                                                <?php
                                                $titlequery = "SELECT `posts`.`title`, `posts`.`post_id` FROM `posts` WHERE `posts`.`published`='1'";
                                                $titleresult = mysqli_query($conn, $titlequery);

                                                ?>
                                                <select id='postname' name='postname' required placeholder='Select'>
                                                    <?php while($row = mysqli_fetch_array($titleresult)) {
                                                        $title = $row['title'];
                                                        $post_id = $row['post_id'];
                                                        echo "<option value='$post_id'>$title</option>";
                                                    } ?>
                                                </select></h5></td>
                                            </tr>
                                        </table>
                                        <input type='submit' name='newcommmake' style="background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px">
                                        <button value="Cancel" style="background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px" onclick="window.location.href='commenttable.php'">Cancel</button>
                                    </form>


                                    
                                    
                                    
                                    
                               <?php }?>
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