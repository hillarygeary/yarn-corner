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

$query = "SELECT posts.post_id, users.username, 
        posts.timestamp, posts.title, posts.content, 
        posts.published, posts.featured 
        FROM posts INNER JOIN `users` 
        ON `users`.`user_id`=`posts`.`user_id`
        ORDER BY `posts`.`timestamp` DESC";
$result = mysqli_query($conn, $query);

$table = True;
$edit = False;
$complete = False;

if(isset($_POST['submit']) && !empty($_POST['select'])) {
    $edit = True;
    $table = False;
    $postedit = $_POST['select'];
    $_SESSION['postedit'] = $postedit;
}
if(!isset($postedit) && isset($_SESSION['postedit'])) {
    $postedit = $_SESSION['postedit'];
}

if(isset($_POST['editsubmit'])) {
    $post_id = $_POST['post_id'];

    if(isset($_POST['newpublished'])) {
        $published = 1;
    } else {
        $published = 0;
    }

    if(isset($_POST['newfeatured'])) {
        $featured = 1;
    } else {
        $featured = 0;
    }
    $content = $_POST['content'];
    $title = $_POST['title'];

    $pic = $_POST['pic'];

    if(isset($_POST['category'])) {
        $insertcontent = mysqli_escape_string($conn, $content);
        $inserttitle = mysqli_escape_string($conn, $pic);
        $insertpic = mysqli_escape_string($conn, $pic);
        $newcats = $_POST['category'];
        $ogcats = $_POST['ogcats'];

        $editquery = "UPDATE `posts` SET `featured` = '$featured' WHERE `posts`.`post_id` = '$post_id';";
        mysqli_query($conn, $editquery) or die("bad query");

        $catupdate = "UPDATE `categoryposts` SET `active` = '0' 
                        WHERE `categoryposts`.`post_id`='$post_id'";

        mysqli_query($conn, $catupdate) or die("bad query");

        foreach($newcats as $key => $value) {
            $catupdate = "INSERT INTO `categoryposts` (`catp_id`, `post_id`, `cat_id`) VALUES (NULL, '$post_id', '$value');";
            mysqli_query($conn, $catupdate);
        }

        if(!empty($newpic)) { 
            $updatequery = "UPDATE `posts` SET `title`='$inserttitle', `content`='$insertcontent', `pic`='$insertpic' WHERE `posts`.`post_id`='$post_id';";
        } else {
            $updatequery = "UPDATE `posts` SET `title`='$inserttitle', `content`='$insertcontent' WHERE `posts`.`post_id`='$post_id';";
        }
        mysqli_query($conn, $updatequery) or die("bad title/content query");

        $edit = False;
        $table = True;
        header('Refresh:0');
    } else {
        $msg = "Please select a category";
        $edit = True;
        $table = False;
    }
}

if(isset($_POST['newpost'])) {
    header('location:adminmakepost.php');
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

    <title>Yarn Corner Posts</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

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
            <li class="nav-item active">
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
                    <h1 class="h3 mb-2 text-gray-800" style='padding-top:20px;padding-bottom:20px'>Posts</h1>

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
                                                <th>Post ID</th>
                                                <th>User</th>
                                                <th>Timestamp</th>
                                                <th>Title</th>
                                                <th>Content</th>
                                                <th>Published</th>
                                                <th>Featured</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Select</th>
                                                <th>Post ID</th>
                                                <th>User</th>
                                                <th>Timestamp</th>
                                                <th>Title</th>
                                                <th>Content</th>
                                                <th>Published</th>
                                                <th>Featured</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            
                                            <?php
                                            while($row = mysqli_fetch_array($result)) {
                                                $id = $row['post_id'];
                                                ?>
                                                <tr>
                                                    <td><input type='radio' name='select' value='<?php echo $id; ?>'></td>
                                                    <td><?php echo $row['post_id']; ?></td>
                                                    <td><?php echo $row['username']; ?></td>
                                                    <td><?php echo $row['timestamp']; ?></td>
                                                    <td><?php echo $row['title']; ?></td>
                                                    <td>
                                                        <?php 
                                                        $content = $row['content'];
                                                        $contentwrds = explode(' ', $content);
                                                        if(count($contentwrds) < 40) {
                                                            echo $content;
                                                        } else {
                                                            for($i = 0; $i < 40; $i++) {
                                                                echo $contentwrds[$i] . " ";
                                                                if($i == 39) {
                                                                    echo "...";
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php 
                                                        $published = $row['published'];
                                                        if($published == 1) {
                                                            echo "Published";
                                                        } else {
                                                            echo "Not Published";
                                                        }
                                                    
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        $featured = $row['featured'];
                                                        if($featured == 1) {
                                                            echo "Featured";
                                                        } else {
                                                            echo "Not Featured";
                                                        }
                                                    
                                                        ?>
                                                    </td>
                                                    

                                            <?php } ?>
                                        </tbody>
                                    </table>
                                                <input type='submit' name='submit' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                            </form><br>
                                            <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                <input type='submit' name='newpost' value='New Post' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                            </form>
                                </div> <?php } elseif($edit) { 
                                    $postquery = "SELECT `posts`.`post_id`, `users`.`username`, 
                                                `posts`.`timestamp`, `posts`.`title`, `posts`.`content`, 
                                                `posts`.`published`, `posts`.`featured`, `posts`.`pic`
                                                FROM `posts` INNER JOIN `users` ON `users`.`user_id`=`posts`.`user_id`
                                                WHERE `posts`.`post_id`='$postedit'";
                                    $postresult = mysqli_query($conn, $postquery);
                                    while($row = mysqli_fetch_array($postresult)) {
                                        $post_id = $row['post_id'];
                                        $username = $row['username'];
                                        $timestamp = $row['timestamp'];
                                        $title = $row['title'];
                                        $content = $row['content'];
                                        $published = $row['published'];
                                        $featured = $row['featured'];
                                        $pic = $row['pic'];
                                    }
                                    ?>
                                    <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div><h3>Edit Post</h3><br>
                                    Published: <label class='switch'><input type='checkbox' name='newpublished' <?php if($published == 1) { echo "checked"; } else { echo "unchecked"; } ?>><span class='slider round'></span></label>
                                    <br>
                                    Featured: <label class='switch'><input type='checkbox' name='newfeatured' <?php if($featured == 1) { echo "checked"; } else { echo "unchecked"; } ?>><span class='slider round'></span></label>
                                    <br><br>

                                    <!-- <h4><?php echo $title; ?></h4><br>
                                    <h5><?php echo $username . " - " . $timestamp; ?> </h5>
                                    <p><?php echo $content; ?></p> -->

                                    

                                    
                                    <!-- hidden input to check for edited fields -->
                                    <input type='text' name='post_id' value='<?php echo $post_id; ?>' readonly hidden>
                                    <input type='text' name='ogpublished' value='<?php echo $published; ?>' readonly hidden>
                                    <input type='text' name='ogfeatured' value='<?php echo $featured; ?>' readonly hidden>
                                    
                                
                                    <!-- </form> -->
                                    <?php

                                    $query = "SELECT * FROM  `categories` WHERE `categories`.`published`='1'";
                                    $result = mysqli_query($conn, $query);

                                    $catquery = "SELECT `categories`.`cat_id`, `categories`.`cat_name`
                                                    FROM `categories`
                                                    INNER JOIN `categoryposts` ON `categoryposts`.`cat_id`=`categories`.`cat_id`
                                                    INNER JOIN `posts` ON `posts`.`post_id`=`categoryposts`.`post_id`
                                                    WHERE `posts`.`post_id`='$post_id'";
                                    $catresult = mysqli_query($conn, $catquery);
                                    $catnamelist = [];
                                    $catidlist = [];
                                    while($row = mysqli_fetch_array($catresult)) {
                                        array_push($catnamelist, $row['cat_name']);
                                        array_push($catidlist, $row['cat_id']);
                                    }
                                    ?>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="padding:20px">
                                        
                                        <h4>Title: <input type='text' name='title' value='<?php echo $title; ?>' size='40'></h4><br>
                                        <?php if(isset($msg)) { echo "<h3 style='color: darkred'>$msg</h3>"; } ?>
                                        <?php while($row = mysqli_fetch_array($result)) { 
                                            $cat_id = $row['cat_id'];
                                            $cat_name = $row['cat_name'];
                                            ?>
                                            <input type='checkbox' name='category[]' value='<?php echo $cat_id; ?>'<?php if(in_array($cat_name, $catnamelist)) { echo "checked"; }?>><?php echo $cat_name;?><br>
                                        <?php } ?>
                                        <br>

                                        <h5>New Image: <input type='text' name='pic'></h5>
                                        <?php if(!empty($pic)) { ?> <h5>Current Image: <br><br><img src="<?php echo $pic; ?>" width='50%'> <?php } ?>
                                        <br><br>

                                        <textarea name="content" id="editor">
                                            <?php if(isset($content)) { echo $content; } else { ?>&lt;p&gt;This is some sample content.&lt;/p&gt; <?php } ?>
                                        </textarea><br>

                                        <input type='submit' name='editsubmit' style='background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px'>
                                        <button value="Cancel" style="background-color:#4e73df;color:white;border:0px;padding:6px;border-radius:5px" onclick="window.location.href='posttable.php'">Cancel</button>
                                           
                                        <input type='text' name='ogcats[]' value='<?php echo $catidlist; ?>' hidden readonly>
                                    </form>
                                    <script>
                                        ClassicEditor
                                            .create( document.querySelector( '#editor' ) )
                                            .catch( error => {
                                                console.error( error );
                                            } );
                                    </script>
                                
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