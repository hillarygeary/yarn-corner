<?php
session_start();
if(!isset($_SESSION['level']) OR $_SESSION['level'] == 1) {
    header('location:index.php');
}

require('required/conn.php');
// $conn = mysqli_connect('localhost', 'root', '', 'geary') or die('bad db conn');

$query = "SELECT posts.post_id, users.username, 
        posts.timestamp, posts.title, posts.content, 
        posts.published, posts.featured 
        FROM posts INNER JOIN `users` 
        ON `users`.`user_id`=`posts`.`user_id`";
$result = mysqli_query($conn, $query);

$table = True;
$edit = False;
$complete = False;

if(isset($_POST['newpostmake'])) {

    $title = $_POST['title'];
	$content = $_POST['content'];
	$pic = $_POST['pic'];


    if(isset($_POST['published'])) {
        $published = 1;
    } else {
        $published = 0;
    }

    if(isset($_POST['featured'])) {
        $featured = 1;
    } else {
        $featured = 0;
    }


	
	if(!empty($_POST['category'])) {
		
		// echo $content;
		$user_id = $_SESSION['id'];
        $inserttitle = mysqli_escape_string($conn, $title);
        
        $insertcontent = mysqli_escape_string($conn, $content);
        $insertpic = mysqli_escape_string($conn, $pic);
		
		$query = "INSERT INTO `posts` (`post_id`, `user_id`, `timestamp`, 
			`title`, `content`, `pic`, `finished`, `published`, `featured`) 
			VALUES (NULL, '$user_id', current_timestamp(), '$inserttitle', '$insertcontent', '$insertpic', '1', '$published', '$featured');";
			mysqli_query($conn, $query) or die("bad 1 query");

		// grab post id, create a file to match the post
		$query = "SELECT `post_id` FROM `posts` WHERE `user_id`='$user_id' AND `title`='$inserttitle' AND `content`='$insertcontent'";
		$result = mysqli_query($conn, $query) or die("bad 2 query");
		while($row = mysqli_fetch_array($result)) {
			$post_id = $row['post_id'];
		} 

		foreach($_POST['category'] as $key => $value) {
			$query = "INSERT INTO `categoryposts` (`catp_id`, `post_id`, `cat_id`) VALUES (NULL, '$post_id', '$value');";
			mysqli_query($conn, $query) or die("bad 3 query");
		}
        $newpost = False;
        $table = True;
        header('location: posttable.php');
	} else {
		$msg = 'Please select a category';
		$makepost = True;

	}	


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

                            <h3>New Post</h3>
                            <h3 style="padding-left:20px"><?php if(isset($msg)) { echo $msg; }?> </h3>
                                <?php

                                $query = "SELECT * FROM  `categories` WHERE `categories`.`published`='1'";
                                $result = mysqli_query($conn, $query);
                                ?>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="padding:20px">
                                    Published: <label class='switch'><input type='checkbox' name='newpublished' checked><span class='slider round'></span></label>
                                    <br>
                                    Featured: <label class='switch'><input type='checkbox' name='newfeatured' unchecked><span class='slider round'></span></label>
                                    <h5>Title: <input type='text' name='title' <?php if(isset($title)) { echo "value='$title'"; } ?> required></h5><br>
                                    <?php while($row = mysqli_fetch_array($result)) { 
                                        $cat_id = $row['cat_id'];
                                        $cat_name = $row['cat_name'];
                                        ?>
                                        <input type='checkbox' name='category[]' value='<?php echo $cat_id; ?>'> <?php echo $cat_name;?><br>
                                    <?php } ?>
                                    <br><h5>Display Image: <input type='url' name='pic' <?php if(isset($pic)) { echo "value='$pic'"; } ?>></h5>
                                    <p>This image will be displayed above your post content.</p><br>
                                    <textarea name="content" id="editor" width='50%' <?php if(!isset($content)) { echo "placeholder='Enter your content here...'"; } ?> required>
                                        <?php if(isset($content)) { echo $content; } ?>
                                    </textarea><br>
                                    <input type="submit" name='newpostmake' class="btn py-3 px-4 btn-primary">
                                    <button value="Cancel" class="btn py-3 px-4 btn-primary" onclick="window.location.href='posttable.php'">Cancel</button>
                                </form>
                                <script>
                                    ClassicEditor
                                        .create( document.querySelector( '#editor' ) )
                                        .catch( error => {
                                            console.error( error );
                                        } );
                                </script>

                               
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