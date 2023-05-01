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
	$id = null;
}


require('required/conn.php');
require('pages.php');


$page = 'Home';
$search = False;

$query = "SELECT posts.title, posts.timestamp, users.username, 
			posts.content, posts.post_id, posts.pic, users.user_id
		FROM posts 
		INNER JOIN `users` ON `users`.`user_id`=`posts`.`user_id`
		WHERE `posts`.`published`='1' AND `users`.`enabled`='1'
		ORDER BY `posts`.`timestamp` DESC";


if (isset($_GET['cat_id'])) {
	$cat_id = $_GET['cat_id'];
	$catnamequery = "SELECT categories.cat_name FROM categories WHERE categories.cat_id='$cat_id'";
	$catnameresult = mysqli_query($conn, $catnamequery);

	while ($row = mysqli_fetch_array($catnameresult)) {
		$cat_selected_name = $row['cat_name'];
	}

	$query = "SELECT posts.title, posts.timestamp, users.username, 
				posts.content, posts.post_id, categories.cat_name, posts.pic, users.user_id
			FROM posts 
			INNER JOIN `users` ON `users`.`user_id`=`posts`.`user_id`
			INNER JOIN `categoryposts` ON `categoryposts`.`post_id`=`posts`.`post_id`
			INNER JOIN `categories` ON `categories`.`cat_id`=`categoryposts`.`cat_id`
			WHERE `categories`.`cat_id`='$cat_id' AND `categoryposts`.`active`='1' AND `posts`.`published`='1' AND `users`.`enabled`='1'
			ORDER BY `posts`.`timestamp` DESC";

}

$result = mysqli_query($conn, $query);

if(isset($_POST['submit'])) {
	header("location: blog.php");
	$_SESSION['post_id'] = $_POST['post_id'];
}

if(isset($_POST['usersearch'])) {
	$search = True;
	$usersearch = strtolower($_POST['usersearch']);
}




?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Yarn Corner</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
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

		.titlebutton {
			background-color: transparent;
			background-repeat: no-repeat;
			border: none;
			cursor: pointer;
			overflow: hidden;
			outline: none;
		}

		.plswork {
			text-align: center;
		}

		#fullwidth {
			width: 100%;
		}

		#header {
			background: #ffffff; /* Grey background */
			border: 1px;
			padding: 10px 16px; /* Some padding */
			color: black;
			text-align: center; /* Centered text */
			font-size: 20px; /* Big font size */
			font-weight: bold;
			position: fixed; /* Fixed position - sit on top of the page */
			top: 0;
			width: 75%; /* Full width */
			transition: 0.2s; /* Add a transition effect (when scrolling - and font size is decreased) */
			/* font-family: "Poppins", Arial, sans-serif; */
			font-family: "Lora", Arial, serif;
		}

		.searchbar {
			border-radius:6px;
			border-color: lightgrey;
		}
		
		

	</style>
  </head>
  <body>

  	

	<div id="colorlib-page">
		
		<a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
		<aside id="colorlib-aside" role="complementary" class="js-fullheight">
			<h1 id="colorlib-logo" class="mb-4"><a href="index.php" style="background-image: url(images/yarnbg2.jpg);">YARN<span>CORNER</span></a></h1>

			<?php showpages($page, $id); ?>

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
						<?php echo "<a href='index.php?cat_id=$cat_id'>$cat_name</a>"; ?>
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
			
			<section class="ftco-section ftco-no-pt ftco-no-pb" style="padding-left:400px">
			<div id="header">
				

			</div>
			
			
	    	<div class="container">
	    		<div class="row d-flex">
	    			<div class="col-xl-8 py-5 px-md-5">
	    				<form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<div class="form-group">
								
								<input type="text" size='100%' name='usersearch' placeholder="Search all posts..." class='searchbar'>
								
							</div>
						</form>
						<?php



						if($search) { ?>
							<h1>Search Results: <?php echo $usersearch; ?></h1>
							<h5><a href='index.php' style='color:black'>Return to Home</a></h5><br>

							<?php
							while($row = mysqli_fetch_array($result)) {

								$aucontent = $row['content'];
								$contentwrds = explode(' ', $aucontent);
								
								$autitle = $row['title'];
								$autimestamp = $row['timestamp'];
								$auname = $row['username'];
								$aupost_id = $row['post_id'];
								$aupicture = $row['pic'];
								$auuser_id = $row['user_id'];

								if (empty($aupicture)) {
									$aupicture = "\images\noblogpicturesidebar.jpg";
								}

								$titlelower = strtolower($autitle);
								$userlower = strtolower($auname);
								$contentlower = strtolower($aucontent);

								if(strpos($contentlower, $usersearch)!= False OR strpos($titlelower, $usersearch)!= False OR $usersearch == $auname) { ?>
										<div class="col-md-12">
											<div class="blog-entry ftco-animate d-md-flex">
												<?php echo "<a href='blog.php?post_id=$aupost_id' class='img img-2' style='background-image: url($aupicture);'></a>"; ?>
												<div class="text text-2 pl-md-4">
										<?php echo "<h3 class='mb-2'><a href='blog.php?post_id=$aupost_id'>$autitle</a></h3>"; ?>
										

									<div class="meta-wrap">
														<p class="meta">

											<?php echo "<span><a href='bloglist.php?userdispl=$auuser_id'><i class='icon-person mr-2'd></i>$auname</a></span>"; ?>
											<span><i class="icon-calendar mr-2"></i><?php echo $autimestamp; ?></span>
										</p>
									</div>
									<p class="mb-4"><?php if(count($contentwrds) < 20) {
													echo $aucontent;
													} else {
														for($i = 0; $i < 20; $i++) {
															echo $contentwrds[$i] . " ";
															if($i == 19) {
																echo "...";
															}
														}
													} ?></p>
									<?php echo "<p><a href='blog.php?post_id=$aupost_id' class='btn-custom'>Read More <span class='ion-ios-arrow-forward'></span></a></p>"; ?>
								
									</div>
											</div>
										</div> <?php
									$yespost = True;


								} else {
									$noresults = True;
									?>
									
									<?php
								}
								
							}
							if(!isset($yespost)) {
								?>
								<div width='100%'>
								<p><h3>No results</h3></p>
								</div>
							
								<?php
							}




						} else {
						?><div class="row pt-md-4"><?php
						if(isset($cat_selected_name)) { 
							?>
							<div class="col-md-12">
							<div class="text text-2 pl-md-4">
							<h1><?php echo $cat_selected_name; ?></h1>
							<p><a href='index.php' style='color:black;padding-left:20px'><u>Remove category filter</u></a></p>
							<hr>
						</div>
							
						</div>

							<?php
						} ?>

						
						<br><br>

						<div class="col-md-12">
							<div class="text text-2 pl-md-4">
								<div class="blog-entry ftco-animate">


							<?php if(!isset($_GET['cat_id'])) { ?>
						
									<h1 class='mb-2'>Featured Posts</h1><hr></div></div></div>
									<hr style='color:grey'>
									<?php
									$authorquery = "SELECT `posts`.`post_id`, `users`.`username`, `posts`.`timestamp`, `posts`.`title`, 
															`posts`.`content`, `posts`.`pic`, `users`.`user_id`
													FROM `posts` 
													INNER JOIN `users` ON `posts`.`user_id`=`users`.`user_id`
													WHERE `posts`.`featured`='1' AND `posts`.`published`='1'  AND `users`.`enabled`='1'
													ORDER BY `posts`.`timestamp` DESC;";
									$authorresult = mysqli_query($conn, $authorquery);
									while($row = mysqli_fetch_array($authorresult)) {

										$aucontent = $row['content'];
										$contentwrds = explode(' ', $aucontent);
										$autitle = $row['title'];
										$autimestamp = $row['timestamp'];
										$auname = $row['username'];
										$aupost_id = $row['post_id'];
										$aupicture = $row['pic'];
										$auuser_id = $row['user_id'];

										if (empty($aupicture)) {
											$aupicture = "\images\noblogpicturesidebar.jpg";
										}
										?>

							<div class="col-md-12">
									<div class="blog-entry ftco-animate d-md-flex">
										<?php echo "<a href='blog.php?post_id=$aupost_id' class='img img-2' style='background-image: url($aupicture);'></a>"; ?>
										<div class="text text-2 pl-md-4">
								<?php echo "<h3 class='mb-2'><a href='blog.php?post_id=$aupost_id'>$autitle</a></h3>"; ?>
								

				              <div class="meta-wrap">
												<p class="meta">

									<?php echo "<span><a href='bloglist.php?userdispl=$auuser_id'><i class='icon-person mr-2'd></i>$auname</a></span>"; ?>
				              		<span><i class="icon-calendar mr-2"></i><?php echo $autimestamp; ?></span>
				              	</p>
			              	</div>
				              <p class="mb-4"><?php if(count($contentwrds) < 20) {
													echo $aucontent;
													} else {
														for($i = 0; $i < 20; $i++) {
															echo $contentwrds[$i] . " ";
															if($i == 19) {
																echo "...";
															}
														}
													} ?></p>
							  <?php echo "<p><a href='blog.php?post_id=$aupost_id' class='btn-custom'>Read More <span class='ion-ios-arrow-forward'></span></a></p>"; ?>
				            </div>
									</div>
								</div>
									<?php
									} ?>
						
						
						
						<div class="col-md-12">
							<div class="text text-2 pl-md-4">
								<div class="blog-entry ftco-animate">
						
									<h1 class='mb-2'>All Posts</h1><hr> <?php
						} ?>
						<div class="col-md-12">
								<div class="blog-entry ftco-animate">  <?php 
						while($row = mysqli_fetch_array($result)) {

							$content = $row['content'];
							$contentwrds = explode(' ', $content);
							$post_id = $row['post_id'];
							$posttitle = $row['title'];
							$author = $row['username'];
							$timestamp = $row['timestamp'];
							$picture = $row['pic'];
							$author_id = $row['user_id'];

							if (empty($picture)) {
								$picture = "\images\noblogpicture.jpg";
							}
							
						?>
						

						
			    			<div class="col-md-12">
									<div class="blog-entry ftco-animate d-md-flex">
										<?php echo "<a href='blog.php?post_id=$post_id' class='img img-2' style='background-image: url($picture);'></a>"; ?>
										<div class="text text-2 pl-md-4">
								<?php echo "<h3 class='mb-2'><a href='blog.php?post_id=$post_id'>$posttitle</a></h3>"; ?>
								

				              <div class="meta-wrap">
												<p class="meta">

									<?php echo "<span><a href='bloglist.php?userdispl=$author_id'><i class='icon-person mr-2'd></i>$author</a></span>"; ?>
				              		<span><i class="icon-calendar mr-2"></i><?php echo $timestamp; ?></span>
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
				            </div>
									</div>
								</div>

								
						<?php } 
						
						}?>
								
			    		</div><!-- END-->
			    		<div class="row">
			          <div class="col">
			          </div>
			        </div>
			    	</div>			

	            
	          <!-- </div>END COL -->
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

