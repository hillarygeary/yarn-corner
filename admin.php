
<?php

/*

Hello! Just as a quick note, this code is pretty poorly documented, that's something
I'm aware of and I'm working on updating it in my free time!
Just know that documentation in my more recent programs is much more thorough and clean!

*/

require('required/conn.php');

session_start();

if(!isset($_SESSION['id']))  {
    header('location:index.php');
}

if($_SESSION['level'] == 1) {
    header('location:index.php');
}

$conn = mysqli_connect('localhost', 'root', '', 'geary') or die('bad db conn');

$catquery = "SELECT `categories`.`cat_id`, `categories`.`cat_name` FROM `categories` WHERE `categories`.`published`='1';";
$result = mysqli_query($conn, $catquery);
$catidlist = [];
$catnames = [];

$colourarray = ['#845EC2', '#D65DB1', '#FF6F91', '#FF9671', '#FFC75F', '#F9F871', '#9BDE7E', '#4BBC8E', '#039590', '#1C6E7D'];

while($row = mysqli_fetch_array($result)) {
	array_push($catidlist, $row['cat_id']);
	array_push($catnames, $row['cat_name']);
}

$catviews = [];
foreach($catidlist as $key => $value) {
	$catquery = "SELECT `posts`.`views`
				FROM `posts`
				INNER JOIN `categoryposts` ON `categoryposts`.`post_id`=`posts`.`post_id`
				INNER JOIN `categories` ON `categories`.`cat_id`=`categoryposts`.`cat_id`
				WHERE `categories`.`cat_id`='$value' AND `categoryposts`.`active`='1' AND `posts`.`published`='1'";
	$result = mysqli_query($conn, $catquery);
	$counter = 0;

	while($row = mysqli_fetch_array($result)) {
		$counter += $row['views'];
	}
	array_push($catviews, $counter);
}

$maxviews = 0;
foreach($catviews as $key => $value) {
    if($value >= $maxviews) {
        $maxviews = $value;
    }
}
$maxviews = round($maxviews + 10, -1);

$catposts = [];
$totalposts = 0;
foreach($catidlist as $key => $value) {
	$catquery = "SELECT `posts`.`post_id`
				FROM `posts`
				INNER JOIN `categoryposts` ON `categoryposts`.`post_id`=`posts`.`post_id`
				INNER JOIN `categories` ON `categories`.`cat_id`=`categoryposts`.`cat_id`
				WHERE `categories`.`cat_id`='$value' AND `categoryposts`.`active`='1' AND `posts`.`published`='1'";
	$result = mysqli_query($conn, $catquery);
	$counter = 0;

	while($row = mysqli_fetch_array($result)) {
		$counter++;
	}
	array_push($catposts, $counter);
}

$totalpostquery = "SELECT `posts`.`post_id` FROM `posts` WHERE `posts`.`published`='1'";
foreach($catposts as $key => $value) {
    $totalposts++;
}

foreach($catposts as $key => $value) {
    $value = $value / $totalposts * 100;
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

    <title>Yarn Corner Admin</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .dot {
            height: 2px;
            width: 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
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
            <li class="nav-item active">
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

                
               
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4" style='padding-top:20px'>
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
						
                        
                    </div>

                    
                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Views By Category</h6>
                                   
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Posts by Category</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">

                                        <?php for($i = 0; $i < count($catidlist); $i++) { ?>
                                            <div>
                                            <span class="mr-2">
                                                <span style='height:8px; width:8px; background-color:<?php echo $colourarray[$i]; ?>; border-radius: 50%; display: inline-block; padding: 3px; margin:auto'></span><b> <?php echo $catnames[$i]; ?></b>
                                            </span>
                                            </div>
                                            <?php
                                        } ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                  
                        </div>
                    </div>

                </div>

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
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>


    <!-- CHART SCRIPTS, USED LIBRARY CHART.JS -->
	<script>
		

		// Set new default font family and font color to mimic Bootstrap's default styling
		Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
		Chart.defaults.global.defaultFontColor = '#858796';

		function number_format(number, decimals, dec_point, thousands_sep) {
		// *     example: number_format(1234.56, 2, ',', ' ');
		// *     return: '1 234,56'
		number = (number + '').replace(',', '').replace(' ', '');
		var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function(n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
			};
		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
		}

        

		// VIEWS BY CATEGORY BAR CHART
		var ctx = document.getElementById("myBarChart");
		var myBarChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: [<?php foreach($catnames as $key => $value) { if($key == count($catnames) - 1) { echo "'$value'"; } else { echo "'$value'" . ', '; } } ?>],
			datasets: [{
			label: "Views",
			backgroundColor: "#4e73df",
			hoverBackgroundColor: "#2e59d9",
			borderColor: "#4e73df",
			data: [<?php foreach($catviews as $key => $value) { if($key == count($catviews) - 1) { echo "'$value'"; } else { echo "'$value'" . ', '; } } ?>],
			}],
		},
		options: {
			maintainAspectRatio: false,
			layout: {
			padding: {
				left: 10,
				right: 25,
				top: 25,
				bottom: 0
			}
			},
			scales: {
			xAxes: [{
				time: {
				unit: 'month'
				},
				gridLines: {
				display: false,
				drawBorder: false
				},
				ticks: {
				maxTicksLimit: 6
				},
				maxBarThickness: 25,
			}],
			yAxes: [{
				ticks: {
				min: 0,
				max: <?php echo $maxviews; ?>,
				maxTicksLimit: 5,
				padding: 10,
				// Include a dollar sign in the ticks
				// callback: function(value, index, values) {
				// 	return '$' + number_format(value);
				// }
				},
				gridLines: {
				color: "rgb(234, 236, 244)",
				zeroLineColor: "rgb(234, 236, 244)",
				drawBorder: false,
				borderDash: [2],
				zeroLineBorderDash: [2]
				}
			}],
			},
			legend: {
			display: false
			},
			tooltips: {
			titleMarginBottom: 10,
			titleFontColor: '#6e707e',
			titleFontSize: 14,
			backgroundColor: "rgb(255,255,255)",
			bodyFontColor: "#858796",
			borderColor: '#dddfeb',
			borderWidth: 1,
			xPadding: 15,
			yPadding: 15,
			displayColors: false,
			caretPadding: 10,
			callbacks: {
				label: function(tooltipItem, chart) {
				var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
				return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
				}
			}
			},
		}
		});
	</script>

    <script>
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';





        // POSTS BY CATEGORY PIE CHART
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [<?php foreach($catnames as $key => $value) { if($key == count($catnames) - 1) { echo "'$value'"; } else { echo "'$value'" . ', '; } } ?>],
            datasets: [{
            data: [<?php foreach($catposts as $key => $value) { if($key == count($catposts) - 1) { echo "'$value'"; } else { echo "'$value'" . ', '; } } ?>],
            backgroundColor: [<?php foreach($colourarray as $key => $value) { if($key == count($colourarray) - 1) { echo "'$value'"; } else { echo "'$value'" . ', '; } } ?>],
            hoverBackgroundColor: [<?php foreach($colourarray as $key => $value) { if($key == count($colourarray) - 1) { echo "'$value'"; } else { echo "'$value'" . ', '; } } ?>],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            },
            legend: {
            display: false
            },
            cutoutPercentage: 80,
        },
        });
    </script>



</body>

</html>