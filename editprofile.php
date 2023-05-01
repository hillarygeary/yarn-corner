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

if(isset($_GET['userdispl'])) {
	$user_id = $_GET['userdispl'];
	$_SESSION['userdispl'] = $user_id;
} else {
	$user_id = $_SESSION['userdispl'];
}


$userquery = "SELECT users.user_id, users.username, users.fname, 
				users.lname, users.email, users.bio, users.instagram,
				users.twitter, users.youtube, users.etsy
				FROM users WHERE users.user_id = '$user_id'";
$userresult = mysqli_query($conn, $userquery) or die("bad user query");



if(isset($_POST['editsubmit'])) {
    $user_id = $_SESSION['userdispl'];
    $newusername = mysqli_escape_string($conn, $_POST['newusername']);
    $newfname = mysqli_escape_string($conn, $_POST['newfname']);
    $newlname = mysqli_escape_string($conn, $_POST['newlname']);
    $newemail = mysqli_escape_string($conn, $_POST['newemail']);
    $newpwd = $_POST['pwd'];
    $newbio = mysqli_escape_string($conn, $_POST['bio']);

	$newinstagram = mysqli_escape_string($conn, $_POST['instagram']);
	$newtwitter = mysqli_escape_string($conn, $_POST['twitter']);
	$newyoutube = mysqli_escape_string($conn, $_POST['youtube']);
	$newetsy = mysqli_escape_string($conn, $_POST['etsy']);

	$editquery = "UPDATE `users` SET `username` = '$newusername' WHERE `users`.`user_id` = '$user_id';";
	mysqli_query($conn, $editquery) or die($error = "bad username query");

    $editquery = "UPDATE `users` SET `bio` = '$newbio' WHERE `users`.`user_id` = '$user_id';";
    mysqli_query($conn, $editquery) or die("bad bio query");

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


    header('location: bloglist.php');
}



if(isset($_GET['userdispl'])) {
	$userdispl = $_GET['userdispl'];
	$_SESSION['userdispl'] = $userdispl;
} else {
	$userdispl = $_SESSION['userdispl'];
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
			<!-- <div id="colorlib-main"> -->
			<section class="ftco-section ftco-no-pt ftco-no-pb"  style="padding-left:30%;padding-right:10%">
			<?php

				if(isset($error)) { echo $error; }

			?>
			
				
				 
				
				<?php


					while($row = mysqli_fetch_array($userresult)) {
						$user_id = $row['user_id'];
						$username = $row['username'];
						$fname = $row['fname'];
						$lname = $row['lname'];
						$email = $row['email'];
                        $bio = $row['bio'];
						$instagram = $row['instagram'];
						$twitter = $row['twitter'];
						$youtube = $row['youtube'];
						$etsy = $row['etsy'];
					}
					?>
					<div style='padding:20px'>
					 <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<h1>Edit Profile</h1> <?php if(isset($msg)) { echo $msg; } ?>
						<hr>
						<br>
						<table>
							<tr>
								<td style='padding-right:10px'><h5>Username: </h5></td>
								<td><h5><input type='text' name='newusername' value='<?php echo $username; ?>'></h5></td>
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
						<br>

							<h3>Link your socials!</h3>
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

						<br>
						<h5>Bio: </h5>
                        <textarea name="bio" id="editor" width='50%' placeholder='Enter your bio here...'>
							<?php if(isset($bio)) { echo $bio; } ?>
						</textarea><br>

                        <script>
                            ClassicEditor
                                .create( document.querySelector( '#editor' ) )
                                .catch( error => {
                                    console.error( error );
                                } );
                        </script>


						<input type='text' name='user_id' value='<?php echo $user_id; ?>' readonly hidden>
						<input type='text' name='ogusername' value='<?php echo $username; ?>' readonly hidden>
						<input type='text' name='ogfname' value='<?php echo $fname; ?>' readonly hidden>
						<input type='text' name='oglname' value='<?php echo $lname; ?>' readonly hidden>
						<input type='text' name='ogemail' value='<?php echo $email; ?>' readonly hidden>

						<input type='submit' name='editsubmit' value='Submit Changes' class="btn py-3 px-4 btn-primary"> 
                        <!-- <button value="Cancel" class="btn py-3 px-4 btn-primary" onclick="window.location.href='bloglist.php'">Cancel</button> -->
						</form><br>
						<button value="Cancel" class="btn py-3 px-4 btn-primary" onclick="window.location.href='bloglist.php'">Cancel</button>
					</div>


					
					
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