<?php

/*

Hello! Just as a quick note, this code is pretty poorly documented, that's something
I'm aware of and I'm working on updating it in my free time!
Just know that documentation in my more recent programs is much more thorough and clean!

*/


session_start();
if(isset($_SESSION['id'])) {
	$id = $_SESSION['id'];
} else {
	$id = 0;
}

if(isset($_GET['post_id'])) {
	$post_id = $_GET['post_id'];
	$_SESSION['post_id'] = $post_id;
} else {
	$post_id = $_SESSION['post_id'];
}
$edit = False;
require('pages.php');
$page = "Blog";



require('required/conn.php');
$query = "SELECT users.user_id, posts.title, posts.timestamp, users.username, 
				posts.content, posts.post_id, posts.pic, users.instagram, users.twitter,
				users.youtube, users.etsy, posts.published
				FROM posts INNER JOIN `users` 
				ON `users`.`user_id`=`posts`.`user_id`
				WHERE `post_id`='$post_id'";
$result = mysqli_query($conn, $query);

$viewquery = "SELECT `posts`.`views` FROM `posts` WHERE `posts`.`post_id`='$post_id'";
$viewresult = mysqli_query($conn, $viewquery);
while($row = mysqli_fetch_array($viewresult)) {
	$views = $row['views'];
}
$views++;
$updateviews = "UPDATE `posts` SET `views` = '$views' WHERE `posts`.`post_id` ='$post_id';";
mysqli_query($conn, $updateviews);

while($row = mysqli_fetch_array($result)) {
	$posttitle = $row['title'];
	$author = $row['username'];
	$timestamp = $row['timestamp'];
	$content = $row['content'];
	$user_id = $row['user_id'];
	$pic = $row['pic'];
	$userinstagram = $row['instagram'];
	$usertwitter = $row['twitter'];
	$useryoutube = $row['youtube'];
	$useretsy = $row['etsy'];
	$published = $row['published'];
	if(!empty($userinstagram) OR !empty($usertwitter) OR !empty($useryoutube) OR !empty($useretsy)) {
		$dot = True;
	} else {
		$dot = False;
	}
}

if(isset($_POST['commentsubmit'])) {
	$content = mysqli_escape_string($conn, $_POST['commentcontent']);
	$user_id = $_SESSION['id'];
	$commentsubmitq = "INSERT INTO `comments` (`comment_id`, `user_id`, `post_id`, `timestamp`, `commenttitle`, `content`, `published`) 
				VALUES (NULL, '$user_id', '$post_id', current_timestamp(), '', '$content', '1');";
	mysqli_query($conn, $commentsubmitq) or die('bad query');
	header("Refresh:0");
}

if(isset($_POST['editbutton'])) {
	$edit = True;
}

if(isset($_POST['edit'])) {
	$newcontent = mysqli_escape_string($conn, $_POST['content']);
	$newtitle = mysqli_escape_string($conn, $_POST['title']);
	$newcats = $_POST['category'];
	$ogcats = $_POST['ogcats'];
	$newpic = $_POST['pic'];
	$newpublished = $_POST['newpublished'];

	$editquery = "UPDATE `posts` SET `published` = '$newpublished' WHERE `posts`.`post_id` = '$post_id';";
	mysqli_query($conn, $editquery) or die("bad query");

	$catupdate = "UPDATE `categoryposts` SET `active` = '0' 
					WHERE `categoryposts`.`post_id`='$post_id'";

	mysqli_query($conn, $catupdate) or die("bad query");

	foreach($newcats as $key => $value) {
		$catupdate = "INSERT INTO `categoryposts` (`catp_id`, `post_id`, `cat_id`) VALUES (NULL, '$post_id', '$value');";
		mysqli_query($conn, $catupdate);
	}

	if(!empty($newpic)) { 
		$updatequery = "UPDATE `posts` SET `title`='$newtitle', `content`='$newcontent', `pic`='$newpic' WHERE `posts`.`post_id`='$post_id';";
	} else {
		$updatequery = "UPDATE `posts` SET `title`='$newtitle', `content`='$newcontent' WHERE `posts`.`post_id`='$post_id';";
	}
	mysqli_query($conn, $updatequery) or die("bad title/content query");

	header('Refresh:0');
	if($newpublished == 0) {
		header('location:index.php');
	}
		
	
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Yarn Corner</title>
	<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
	<style>
		/* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 24px;
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
            height: 16px;
            width: 16px;
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
            -webkit-transform: translateX(16px);
            -ms-transform: translateX(16px);
            transform: translateX(16px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 24px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
	</style>
  </head>
  <body>

	<div id="colorlib-page">
		<a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
		<aside id="colorlib-aside" role="complementary" class="js-fullheight">
			<h1 id="colorlib-logo" class="mb-4"><a href="index.php" style="background-image: url(images/yarnbg2.jpg);">YARN<span>CORNER</span></a></h1>
					
			<?php showpages($page, $id); ?>
			

			<div class="colorlib-footer">
				
				<div class="mb-4">
				</div>
				<p class="pfooter"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					Template from <a href="https://colorlib.com" target="_blank">Colorlib</a>
		<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
			</div>
		</aside> <!-- END COLORLIB-ASIDE -->
		<div id="colorlib-main">
			<section class="ftco-section ftco-no-pt ftco-no-pb"  style="padding-left:350px;">
	    	<div class="container">
	    		<div class="row d-flex">
	    			<div class="col-lg-8 px-md-5 py-5">
	    				<div class="row pt-md-4">
						<?php
						if(!$edit) { ?>

							<table cellpadding='0'>
								<tr><td><div><h1 class="mb-3"><?php echo $posttitle; ?></h1></div></tr></td>

								<tr><td>
								<div class="tag-widget post-tag-container ">
								<div class="tagcloud">
									<?php
									$query = "SELECT `categories`.`cat_name`, `categories`.`cat_id`
											FROM `categories`
											INNER JOIN `categoryposts` ON `categoryposts`.`cat_id`=`categories`.`cat_id`
											INNER JOIN `posts` ON `posts`.`post_id`=`categoryposts`.`post_id`
											WHERE `posts`.`post_id`='$post_id' AND `categoryposts`.`active`='1'";
									$catresult = mysqli_query($conn, $query);
									while($row = mysqli_fetch_array($catresult)) {
										$cat_id = $row['cat_id'];
										$cat_name = $row['cat_name'];
										echo "<a class='tag-cloud-link' href='index.php?cat_id=$cat_id'>$cat_name</a>";
										?>
										<!-- <a class="tag-cloud-link"><?php // echo $row['cat_name']; ?></a> -->
									<?php } ?>
									
								</div>
								</div>
								</tr></td>

								<tr><td>
									<div class="desc">
										
										<?php echo "<span><h4><a href='bloglist.php?userdispl=$user_id' style='color:black'>$author</a>"; 
										if($dot) { echo ' - '; } ?>
										
										<?php if(!empty($userinstagram)) {
											?>
											<a href='<?php echo $userinstagram; ?>' target="_blank"><img src='images/instagram.png.webp' width='21px'></a>
											<?php
										}
										if(!empty($usertwitter)) {
											?>
											<a href='<?php echo $usertwitter; ?>' target="_blank"><img src='images/twitter.png' width='23px'></a>
											<?php
										}
										if(!empty($useryoutube)) {
											?>
											<a href='<?php echo $useryoutube; ?>' target="_blank"><img src='images/youtube.png' width='30px'></a>
											<?php
										}
										if(!empty($useretsy)) {
											?>
											<a href='<?php echo $useretsy; ?>' target="_blank"><img src='images/etsy.png' width='18px'></a>
											<?php
										}
										?> </h4></span><p><?php echo $timestamp; ?></p> <?php
										if(isset($_SESSION['id'])) { if($_SESSION['id'] == $user_id OR $_SESSION['level'] == 2) {?>
											<form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
												<input type='submit' name='editbutton' value='Edit Post' class="btn py-3 px-4 btn-primary">
											</form>
											<br>
										<?php }} ?>
									</div>
								</tr></td>	
								<?php if(!empty($pic)) { ?>
									<tr><td>
										<img src='<?php echo $pic; ?>' width='75%'>
									</td></tr>

								<?php } ?>
								</table>
								<br><br>
								<p><?php echo $content; ?></p>

									

						<div class="pt-5 mt-5">
						<h3 class="mb-5 font-weight-bold">Comments</h3>
						<ul class="comment-list">
								<?php
								$commentquery = "SELECT `comments`.`comment_id`, `users`.`username`, `comments`.`timestamp`,
												`comments`.`content`, `comments`.`published`
												FROM `comments`
												INNER JOIN `users` ON `users`.`user_id`=`comments`.`user_id`
												INNER JOIN `posts` ON `posts`.`post_id`=`comments`.`post_id`
												WHERE `comments`.`post_id`='$post_id' AND `comments`.`published`='1' AND `users`.`enabled`='1'";

								$commentresult = mysqli_query($conn, $commentquery);
								if(mysqli_fetch_array($commentresult) == '') {
									echo "No comments yet!";
								} else {
									$commentresult = mysqli_query($conn, $commentquery);
									while($row = mysqli_fetch_array($commentresult)) { 

										?>
										<li class="comment">
												
											<div class="comment-body">
												<h3><?php echo $row['username']; ?></h3>
												<div class="meta"><?php echo $row['timestamp']; ?></div>
												<p><?php echo $row['content']; ?></p>
											</div>
										</li>
										<?php
									}
								}
								

								?>
							
						</ul>
						<!-- END comment-list -->
						
						<div class="comment-form-wrap pt-5" width='100%'>
							<h3 class="mb-5">Leave a comment</h3>
							<!-- <form action="#" > -->
								<?php if(isset($_SESSION['username'])) { ?>
							<form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>" class="p-3 p-md-5 bg-light">

							<div class="form-group">
								<label for="message">Comment:</label>
								<textarea name="commentcontent" id="message" cols="60" rows="10" class="form-control" maxlength='500' required></textarea>
							</div>
							<div class="form-group">
								<input type="submit" value="Post Comment" class="btn py-3 px-4 btn-primary" name='commentsubmit'>
							</div>

							</form>
							<?php } else {
								?>
								<p>Please <a href='login.php'>log in</a> to comment</p>
								<?php

							} ?>
						</div>
						</div>
							</div><!-- END-->
						</div>


							<div class="col-lg-4 sidebar ftco-animate bg-light pt-5">

						<div class="sidebar-box ftco-animate">
						<?php
							$authornumquery = "SELECT `posts`.`post_id` FROM `posts` 
												WHERE `posts`.`published`='1' AND `posts`.`user_id`='$user_id' AND `posts`.`post_id` !='$post_id'";
							$authornumresult = mysqli_query($conn, $authornumquery);
							if(mysqli_fetch_array($authornumresult) == '') {

								?>


									<h3 class="sidebar-heading">Featured Posts</h3>
									<?php
									$authorquery = "SELECT `posts`.`post_id`, `users`.`username`, `posts`.`timestamp`, `posts`.`title`, 
															`posts`.`content`, `posts`.`pic`, `users`.`user_id`
													FROM `posts` 
													INNER JOIN `users` ON `posts`.`user_id`=`users`.`user_id`
													WHERE `posts`.`featured`='1' AND `posts`.`published`='1' AND `posts`.`post_id`!='$post_id' AND `users`.`enabled`='1'
													ORDER BY `posts`.`timestamp` DESC;";
									$authorresult = mysqli_query($conn, $authorquery);
									while($row = mysqli_fetch_array($authorresult)) {
										$autitle = $row['title'];
										$aucontent = $row['content'];
										$autimestamp = $row['timestamp'];
										$auname = $row['username'];
										$aupost_id = $row['post_id'];
										$aupicture = $row['pic'];
										$auuser_id = $row['user_id'];
										if(empty($aupicture)) {
											$aupicture = "\images\noblogpicturesidebar.jpg";
										}
										?>

										<div class="block-21 mb-4 d-flex">
											<?php echo "<a href='blog.php?post_id=$aupost_id' class='blog-img mr-4' style='background-image: url($aupicture);'></a>"; ?>

											<div class="text">
											<?php echo "<h3 class='heading'><a href='blog.php?post_id=$aupost_id'>$autitle</a></h3>"; ?>
											<div class="meta">
												<?php echo "<div><a href='bloglist.php?userdispl=$auuser_id'><span class='icon-person'></span>$auname</a></div>"; ?>
												<div><a href="#"><span class="icon-calendar"></span> <?php echo $autimestamp; ?></a></div>
												
											</div>
											</div>
										</div>

										<?php
									}





							} else {



									?>
									<h3 class="sidebar-heading">Other Posts by this Author</h3>
									<?php
									$authorquery = "SELECT `posts`.`post_id`, `users`.`username`, `posts`.`timestamp`, `posts`.`title`, 
															`posts`.`content`, `posts`.`pic`, `users`.`user_id`
													FROM `posts` 
													INNER JOIN `users` ON `posts`.`user_id`=`users`.`user_id`
													WHERE `posts`.`user_id`='$user_id' AND `posts`.`published`='1' AND `posts`.`post_id`!='$post_id'
													ORDER BY `posts`.`timestamp` DESC;";
									$authorresult = mysqli_query($conn, $authorquery);
									while($row = mysqli_fetch_array($authorresult)) {
										$autitle = $row['title'];
										$aucontent = $row['content'];
										$autimestamp = $row['timestamp'];
										$auname = $row['username'];
										$aupost_id = $row['post_id'];
										$aupicture = $row['pic'];
										$auuser_id = $row['user_id'];
										if(empty($aupicture)) {
											$aupicture = "\images\noblogpicturesidebar.jpg";
										}
										?>

										<div class="block-21 mb-4 d-flex">
											<?php echo "<a href='blog.php?post_id=$aupost_id' class='blog-img mr-4' style='background-image: url($aupicture);'></a>"; ?>

											<div class="text">
											<?php echo "<h3 class='heading'><a href='blog.php?post_id=$aupost_id'>$autitle</a></h3>"; ?>
											<div class="meta">
												<?php echo "<div><a href='bloglist.php?userdispl=$auuser_id'><span class='icon-person'></span>$auname</a></div>"; ?>
												<div><a href="#"><span class="icon-calendar"></span> <?php echo $autimestamp; ?></a></div>
												
											</div>
											</div>
										</div>

										<?php
									}
									?>

									<h3 class="sidebar-heading">Featured Posts</h3>
									<?php
									$authorquery = "SELECT `posts`.`post_id`, `users`.`username`, `posts`.`timestamp`, `posts`.`title`, 
															`posts`.`content`, `posts`.`pic`, `users`.`user_id`
													FROM `posts` 
													INNER JOIN `users` ON `posts`.`user_id`=`users`.`user_id`
													WHERE `posts`.`featured`='1' AND `posts`.`published`='1' AND `posts`.`post_id`!='$post_id' AND `posts`.`user_id`!='$user_id'  AND `users`.`enabled`='1'
													ORDER BY `posts`.`timestamp` DESC;";
									$authorresult = mysqli_query($conn, $authorquery);
									while($row = mysqli_fetch_array($authorresult)) {
										$autitle = $row['title'];
										$aucontent = $row['content'];
										$autimestamp = $row['timestamp'];
										$auname = $row['username'];
										$aupost_id = $row['post_id'];
										$aupicture = $row['pic'];
										$auuser_id = $row['user_id'];
										if(empty($aupicture)) {
											$aupicture = "\images\noblogpicturesidebar.jpg";
										}
										?>

										<div class="block-21 mb-4 d-flex">
											<?php echo "<a href='blog.php?post_id=$aupost_id' class='blog-img mr-4' style='background-image: url($aupicture);'></a>"; ?>

											<div class="text">
											<?php echo "<h3 class='heading'><a href='blog.php?post_id=$aupost_id'>$autitle</a></h3>"; ?>
											<div class="meta">
												<!-- <div><a href="#"><span class="icon-person"></span> <?php //echo $auname; ?></a></div> -->
												<?php echo "<div><a href='bloglist.php?userdispl=$auuser_id'><span class='icon-person'></span>$auname</a></div>"; ?>
												<div><a href="#"><span class="icon-calendar"></span> <?php echo $autimestamp; ?></a></div>
												
											</div>
											</div>
										</div>

										<?php
									}


								}






						?>
						




						</div>
						</div>




					<?php } else {
							$query = "SELECT * FROM  `categories` WHERE `categories`.`published`='1'";
							$result = mysqli_query($conn, $query);

							$catquery = "SELECT `categories`.`cat_id`, `categories`.`cat_name`
											FROM `categories`
											INNER JOIN `categoryposts` ON `categoryposts`.`cat_id`=`categories`.`cat_id`
											INNER JOIN `posts` ON `posts`.`post_id`=`categoryposts`.`post_id`
											WHERE `posts`.`post_id`='$post_id' AND `categories`.`published`='1' AND `categoryposts`.`active`='1'";
							$catresult = mysqli_query($conn, $catquery);
							$catnamelist = [];
							$catidlist = [];
							while($row = mysqli_fetch_array($catresult)) {
								array_push($catnamelist, $row['cat_name']);
								array_push($catidlist, $row['cat_id']);
							}
							?>
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="padding:20px">
								<h4>Title: <input type='text' name='title' value='<?php echo $posttitle; ?>' size='40'></h4><br>
								<?php if(isset($msg)) { echo $msg; } ?>
								Published: <label class='switch'><input type='checkbox' name='newpublished' <?php if($published == 1) { echo "checked"; } else { echo "unchecked"; } ?>><span class='slider round'></span></label><br>
								<?php if($_SESSION['level'] == 1) { ?><b>Be careful- if you unpublish a post, only an admin will be able to republish it</b><br> <?php } ?>
								<br>Categories (select one or multiple):<br>
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
										<input type="submit" name='edit' class="btn py-3 px-4 btn-primary">
										<button value="Cancel" class="btn py-3 px-4 btn-primary" onclick="window.location.href='blog.php'">Cancel</button>
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
	    </section>
		</div><!-- END COLORLIB-MAIN -->
	</div><!-- END COLORLIB-PAGE -->

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
    
  </body>
</html>