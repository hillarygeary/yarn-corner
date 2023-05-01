<?php

/*

Hello! Just as a quick note, this code is pretty poorly documented, that's something
I'm aware of and I'm working on updating it in my free time!
Just know that documentation in my more recent programs is much more thorough and clean!

*/

session_start();
$makepost = False;
$editprofile = False;
$page = "My Profile and Blogs";


require('pages.php');
require('required/conn.php');


if(isset($_SESSION['id'])) {
	$user_id = $_SESSION['id'];
} else {
	$user_id = '';
}

$userquery = "SELECT users.user_id, users.username, users.fname, 
					users.lname, users.email, users.bio, users.instagram,
					users.twitter, users.youtube, users.etsy
					FROM users WHERE users.user_id = '$user_id'";
$userresult = mysqli_query($conn, $userquery) or die("bad user query");

if(isset($_POST['makepost'])) {
	$makepost = True;
}


if(isset($_POST['submit'])) {
	$title = $_POST['title'];
	$content = $_POST['content'];
	$pic = $_POST['pic'];
	
	if(!empty($_POST['category'])) {
		
		// echo $content;
		$user_id = $_SESSION['id'];
		$inserttitle = mysqli_escape_string($conn, $title);
		$insertcontent = mysqli_escape_string($conn, $content);
		$insertpic = mysqli_escape_string($conn, $pic);
		
		$query = "INSERT INTO `posts` (`post_id`, `user_id`, `timestamp`, 
			`title`, `content`, `pic`, `finished`, `published`, `featured`) 
			VALUES (NULL, '$user_id', current_timestamp(), '$inserttitle', '$insertcontent', '$insertpic', '1', '1', '0');";
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
	} else {
		$msg = 'Please select a category';
		$makepost = True;

	}	

}


if(isset($_POST['editsubmit'])) {
    $user_id = $_POST['user_id'];
    $newusername = mysqli_escape_string($conn, $_POST['newusername']);
    $newfname = mysqli_escape_string($conn, $_POST['newfname']);
    $newlname = mysqli_escape_string($conn, $_POST['newlname']);
    $newemail = mysqli_escape_string($conn, $_POST['newemail']);
    $newpwd = $_POST['pwd'];
	$newinstagram = mysqli_escape_string($conn, $_POST['instagram']);
	$newtwitter = mysqli_escape_string($conn, $_POST['twitter']);
	$newyoutube = mysqli_escape_string($conn, $_POST['youtube']);
	$newetsy = mysqli_escape_string($conn, $_POST['etsy']);

	$editquery = "UPDATE `users` SET `username` = '$newusername' WHERE `users`.`user_id` = '$user_id';";
	mysqli_query($conn, $editquery) or die($error = "bad username query");

	$editquery = "UPDATE `users` SET 
				`instagram` = '$newinstagram', 
				`twitter` = '$newtwitter', 
				`youtube` = '$newyoutube', 
				`etsy` = '$newetsy' 
				WHERE `users`.`user_id` = '$user_id';";
	mysqli_query($conn, $editquery) or die($error = "bad social media query");

    
    
    
    if($newfname != $_POST['ogfname']) {
        $editquery = "UPDATE `users` SET `fname` = '$newfname' WHERE `users`.`user_id` = '$user_id';";
        mysqli_query($conn, $editquery) or die($error = "bad fname query");
    }
    if($newlname != $_POST['oglname']) {
        $editquery = "UPDATE `users` SET `lname` = '$newlname' WHERE `users`.`user_id` = '$user_id';";
        mysqli_query($conn, $editquery) or die($error = "bad lname query");
    }
    if($newemail != $_POST['ogemail']) {
        $editquery = "UPDATE `users` SET `email` = '$newemail' WHERE `users`.`user_id` = '$user_id';";
        mysqli_query($conn, $editquery) or die($error = "bad email query");
    }
    if($newpwd != '') {
        $pwdhash = password_hash($newpwd, PASSWORD_DEFAULT);
        $editquery = "UPDATE `users` SET `pwd` = '$pwdhash' WHERE `users`.`user_id` = '$user_id';";
        mysqli_query($conn, $editquery) or die($error = "bad pwd query");
    }

    header('Refresh:0');
}



if(isset($_GET['userdispl'])) {
	$userdispl = $_GET['userdispl'];
	$_SESSION['userdispl'] = $userdispl;
} else {
	$userdispl = $_SESSION['userdispl'];
}

if(isset($_POST['editprofile'])) {

	header("location:editprofile.php?userdispl=$userdispl");
}

$userinfoquery = "SELECT `users`.`bio`, `users`.`instagram`,
				`users`.`twitter`, `users`.`youtube`, `users`.`etsy` 
				FROM `users` WHERE `users`.`user_id`='$userdispl'";
$userinforesult = mysqli_query($conn, $userinfoquery);

while($row = mysqli_fetch_array($userinforesult)) {
	$userbio = $row['bio'];
	$userinstagram = $row['instagram'];
	$usertwitter = $row['twitter'];
	$useryoutube = $row['youtube'];
	$useretsy = $row['etsy'];
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
  	<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
    <title>Yarn Corner</title>
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

		.padding {
			padding:15px;
		}

	</style>
  </head>
  <body>

	<div id="colorlib-page">
		<a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
		<aside id="colorlib-aside" role="complementary" class="js-fullheight">

			<h1 id="colorlib-logo" class="mb-4"><a href="index.php" style="background-image: url(images/yarnbg2.jpg);">YARN<span>CORNER</span></a></h1>
			
			<?php showpages($page, $user_id); ?>
			<hr>
			<nav id='colorlib-main-menu' role='navigation'>
				<ul>

				<?php 
				$catquery = "SELECT * FROM `categories` WHERE `categories`.`published`='1'";
				$catresult = mysqli_query($conn, $catquery);
				while($row = mysqli_fetch_array($catresult)) {
					$cat_id = $row['cat_id']; 
					$cat_name = $row['cat_name'];
					?>
					<li>
						<?php echo "<a href='bloglist.php?cat_id=$cat_id'>$cat_name</a>"; ?>
					</li>
					<?php } ?>
					
				</ul>
			</nav>

			<div class="colorlib-footer">
				
				<div class="mb-4">
				</div>
				<p class="pfooter"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				Template from <a href="https://colorlib.com" target="_blank">Colorlib</a>
	  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
			</div>



		</aside> <!-- END COLORLIB-ASIDE -->
		<div id="colorlib-main">
			<section class="ftco-section ftco-no-pt ftco-no-pb"  style="padding-left:30%;padding-right:10%">
			<?php

				$authorq = "SELECT `users`.`username` FROM `users` WHERE `users`.`user_id`='$userdispl'";
				$authorqresult = mysqli_query($conn, $authorq) or die($msg = "bad q");
				while($row = mysqli_fetch_array($authorqresult)) {
					$author = $row['username'];
				}
				if(isset($error)) { echo $error; }

				if($makepost) {
					?> 
					<h1 style="padding:20px">Make a Post</h1><hr>
					<h3 style="padding-left:20px"><?php if(isset($msg)) { echo $msg; }?> </h3>
					<?php

					$query = "SELECT * FROM  `categories` WHERE `categories`.`published`='1'";
					$result = mysqli_query($conn, $query);
					?>


					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="padding:20px">
						<h5>Title: <input type='text' name='title' <?php if(isset($title)) { echo "value='$title'"; } ?> required></h5><br>
						<fieldset id='fieldset' required>
							<legend><h5>Categories (select one or multiple):</h5></legend>
						<?php while($row = mysqli_fetch_array($result)) { 
							$cat_id = $row['cat_id'];
							$cat_name = $row['cat_name'];
							?>
							<input type='checkbox' id='fieldset' name='category[]' value='<?php echo $cat_id; ?>'> <?php echo $cat_name;?><br>
						<?php } ?>
						</fieldset>
						<br><h5>Display Image: <input type='url' name='pic' <?php if(isset($pic)) { echo "value='$pic'"; } ?>></h5>
						<p>This image will be displayed above your post content.</p><br>
						<textarea name="content" id="editor" width='50%' <?php if(!isset($content)) { echo "placeholder='Enter your content here...'"; } ?> required>
							<?php if(isset($content)) { echo $content; } ?>
						</textarea><br>
						<input type="submit" name='submit' class="btn py-3 px-4 btn-primary">
						<button value="Cancel" class="btn py-3 px-4 btn-primary" onclick="window.location.href='bloglist.php'">Cancel</button>
					</form>


					<script>
						ClassicEditor
							.create( document.querySelector( '#editor' ) )
							.catch( error => {
								console.error( error );
							} );
					</script>


					<?php } else {

						$postquery = "SELECT `posts`.`post_id`, `posts`.`title`, `posts`.`content`, `posts`.`timestamp`, `posts`.`pic`
						FROM `posts` INNER JOIN `users` on `users`.`user_id`=`posts`.`user_id` 
						WHERE `users`.`user_id`='$userdispl' AND `posts`.`published`='1'
						ORDER BY `posts`.`timestamp` DESC;";
						$postresult = mysqli_query($conn, $postquery) or DIE($msg = 'bad query');

						if(mysqli_fetch_array($postresult) == '') {
							$msg = "You haven't authored any blogs yet!";
						}

						

						if(isset($_GET['cat_id'])) {
							$cat_id = $_GET['cat_id'];
							$catnamequery = "SELECT categories.cat_name FROM categories WHERE categories.cat_id='$cat_id'";
							$catnameresult = mysqli_query($conn, $catnamequery);
							while($row = mysqli_fetch_array($catnameresult)) {
								$cat_selected_name = $row['cat_name'];
							}
							$postquery = "SELECT posts.title, posts.timestamp, users.username, 
										posts.content, posts.post_id, categories.cat_name, posts.pic
									FROM posts 
									INNER JOIN `users` ON `users`.`user_id`=`posts`.`user_id`
									INNER JOIN `categoryposts` ON `categoryposts`.`post_id`=`posts`.`post_id`
									INNER JOIN `categories` ON `categories`.`cat_id`=`categoryposts`.`cat_id`
									WHERE `categories`.`cat_id`='$cat_id' AND `categoryposts`.`active`='1' AND `users`.`user_id`='$userdispl' 
											AND `posts`.`published`='1'
									ORDER BY `posts`.`timestamp` DESC";
						
						} else {
							$postquery = "SELECT posts.title, posts.timestamp, users.username, 
										posts.content, posts.post_id, posts.pic
									FROM posts 
									INNER JOIN `users` ON `users`.`user_id`=`posts`.`user_id`
									WHERE `users`.`user_id`='$userdispl' AND `posts`.`published`='1'
									ORDER BY `posts`.`timestamp` DESC";
						}



					?> <h1 style="padding-top:20px"><?php echo $author; ?>'s <?php if(isset($cat_selected_name)) { echo $cat_selected_name . ' '; } ?>Blogs</h1> <?php 

					if(isset($cat_selected_name)) { ?><p><a href='bloglist.php' style='color:black;padding-left:20px'><u>Remove category filter</u></a></p> <?php }

					if(isset($msg)) { echo "<h5>$msg</h5><br>"; }
						
					
					if($userdispl == $user_id) { ?>

					
					<table style='border: white 20px;'>
						<td><tr>
							<form method='POST' action='<?php echo $_SERVER['PHP_SELF']; ?>' class='padding'>
								<input type='submit' name='makepost' value='Make a Post' class="btn py-3 px-4 btn-primary">
							</form>
						</tr>
						<tr>
							<i style='color:white'>haa</i>
						</tr>
						<tr>
							<form method='POST' action='<?php echo $_SERVER['PHP_SELF']; ?>'style="padding-left:20px" class='padding'>
								<input type='submit' name='editprofile' value='Edit Profile' class="btn py-3 px-4 btn-primary">
							</form>
						</tr></td>
					</table><br>
					<?php } elseif(isset($_SESSION['level']) AND $_SESSION['level'] == 2) { ?>
							<form method='POST' action='<?php echo $_SERVER['PHP_SELF']; ?>' class='padding'>
								<input type='submit' name='editprofile' value='Edit Profile' class="btn py-3 px-4 btn-primary">
							</form>
					<?php }
					echo $userbio;

					if(!empty($userinstagram)) {
						?>
						<a href='<?php echo $userinstagram; ?>' target="_blank"><img src='images/instagram.png.webp' width='40px'></a>
						<?php
					}
					if(!empty($usertwitter)) {
						?>
						<a href='<?php echo $usertwitter; ?>' target="_blank"><img src='images/twitter.png' width='45px'></a>
						<?php
					}
					if(!empty($useryoutube)) {
						?>
						<a href='<?php echo $useryoutube; ?>' target="_blank"><img src='images/youtube.png' width='50px'></a>
						<?php
					}
					if(!empty($useretsy)) {
						?>
						<a href='<?php echo $useretsy; ?>' target="_blank"><img src='images/etsy.png' width='35px'></a>
						<?php
					}
					


					?><hr><div><?php
						

						$postresult = mysqli_query($conn, $postquery) or DIE($msg = 'bad query');
						
						while($row = mysqli_fetch_array($postresult)) {

							$content = $row['content'];
							$contentwrds = explode(' ', $content);

							$post_id = $row['post_id'];
							$posttitle = $row['title'];
							$timestamp = $row['timestamp'];
							$author = $row['username'];
							$picture = $row['pic'];
							if(empty($picture)) {
								$picture = "\images\noblogpicture.jpg";
							}
							
						?>
			    			<div class="col-md-12">
									<div class="blog-entry ftco-animate d-md-flex">
										<?php echo "<a href='blog.php?post_id=$post_id' class='img img-2' style='background-image: url($picture);'></a>"; ?>
										<!-- <a href="blog.php" class="img img-2" style='background-image: url(<?php //echo $picture; ?>);'></a> -->
										<div class="text text-2 pl-md-4">
								<?php echo "<h3 class='mb-2'><a href='blog.php?post_id=$post_id'>$posttitle</a></h3>"; ?>
								

				              <div class="meta-wrap">
												<p class="meta">
									<?php echo "<span><a href='bloglist.php?userdispl=$userdispl'><i class='icon-person mr-2'd></i>$author</a></span>"; ?>
									<!-- <span><a href="blog.php"><i class="icon-person mr-2"></i><?php //echo $author; ?></a></span> -->
				              		<span><i class="icon-calendar mr-2"></i><?php echo $timestamp; ?></span>
				              		<!-- <span><i class="icon-comment2 mr-2"></i>5 Comment</span> -->
				              	</p>
			              	</div>
				              <p class="mb-4"><?php if(count($contentwrds) < 20) {
													echo $content;
													} else {
														for($i = 0; $i < 20; $i++) {
															echo $contentwrds[$i] . " ";
															if($i == 19) {
																echo "...";
															}
														}
													} ?></p>
							  <?php echo "<p><a href='blog.php?post_id=$post_id' class='btn-custom'>Read More <span class='ion-ios-arrow-forward'></span></a></p>"; ?>
				              <!-- <p><a href="#" class="btn-custom">Read More <span class="ion-ios-arrow-forward"></span></a></p> -->
				            </div>
									</div>
								</div>

								
						<?php } ?>


				<?php } ?>
						</div>

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