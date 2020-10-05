<?php 
require 'DB_PARAMS/connect.php';
function lastnweeks($n) {
	
    $time = strtotime('Monday 00:00:00+0000');
    $arr = array();
    while ($n-- > 0) {
        $arr[] = array_reverse(array(
            'end' => date('Y/m/d', $time-=86400),   // sunday
            'start' => date('Y/m/d', $time-=6*86400)  // monday
        ));
    }
    return $arr;
}
	$PermitActual=0;
	$PermitTarget=0;
	
	$AdvertActual=0;
	$AdvertTarget=0;
	
	$HealthActual=0;
	$HealthTarget=0;
	
	$TransportActual=0;
	$TransportTarget=0;
//permit strings
 $sql="select ServiceGroupID,ServiceGroupName,Total Actual,[Target] from vwReceiptsPerStream";

$result=sqlsrv_query($db,$sql);
while($rrow=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
{ 	
	$ServiceGroup=$rrow['ServiceGroupID'];
	
	if($ServiceGroup=='1')//permits
	{
		$PermitActual=$rrow['Actual'];
		$PermitTarget=$rrow['Target'];
	}else if($ServiceGroup=='12')//adverts
	{
		$AdvertActual=$rrow['Actual'];
		$AdvertTarget=$rrow['Target'];		
	}
	else if($ServiceGroup=='5')//Health
	{
		$HealthActual=$rrow['Actual'];
		$HealthTarget=$rrow['Target'];		
	}
	else if($ServiceGroup=='15')//Transport
	{
		$TransportActual=$rrow['Actual'];
		$TransportTarget=$rrow['Target'];		
	}
}


$Values = array();

$Values['actual'][1] 	= (double)$PermitActual; //Permit
$Values['target'][1] 	= (double)$PermitTarget; //Permit
$Values['percentage'][1]= $Values['actual'][1]/$Values['target'][1] *100;

$Values['actual'][2] = 6295; //Parking
$Values['target'][2] = 6295; //Parking
$Values['percentage'][2]= $Values['actual'][2]/$Values['target'][2] *100;

$Values['actual'][3] = (double)$HealthActual; //Health
$Values['target'][3] = (double)$HealthTarget; //Health
$Values['percentage'][3]= $Values['actual'][3]/$Values['target'][3] *100;

$Values['actual'][4] = (double)$AdvertActual; //Adverts
$Values['target'][4] = (double)$AdvertTarget; //Adverts
$Values['percentage'][4]= $Values['actual'][4]/$Values['target'][4] *100;

$Values['actual'][5] = 6295; //Land Rates
$Values['target'][5] = 6295; //Land Rates
$Values['percentage'][5]= $Values['actual'][5]/$Values['target'][5] *100;

$Values['actual'][6] = (double)$TransportActual; //Transport
$Values['target'][6] = (double)$TransportTarget; //Transport
$Values['percentage'][6]= $Values['actual'][6]/$Values['target'][6] *100;

//print_r(lastnweeks(10));

// Line Graph

$TargetData = "[ [6, 1300], [7, 1600], [8, 1900], [9, 2100], [10, 2500], [11, 2200], [12, 2000], [13, 1950], [14, 1900], [15, 2000] ]";
$ActualData = "[ [6, 500], [7, 600], [8, 550], [9, 600], [10, 800], [11, 900], [12, 800], [13, 850], [14, 830], [15, 1000] ]";

?>
<!DOCTYPE HTML>
<html>
<head>
<title>Minimal an Admin Panel Category Flat Bootstrap Responsive Website Template | Home :: w3layouts</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Minimal Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery.min.js"> </script>
<!-- Mainly scripts -->
<script src="js/jquery.metisMenu.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<!-- Custom and plugin javascript -->
<link href="css/custom.css" rel="stylesheet">
<script src="js/custom.js"></script>
<script src="js/screenfull.js"></script>
		<script>
		$(function () {
			$('#supported').text('Supported/allowed: ' + !!screenfull.enabled);

			if (!screenfull.enabled) {
				return false;
			}

			

			$('#toggle').click(function () {
				screenfull.toggle($('#container')[0]);
			});
			

			
		});
		</script>

<!----->

<!--pie-chart--->
<script src="js/pie-chart.js" type="text/javascript"></script>
 <script type="text/javascript">

        $(document).ready(function () 
		{
            $('#demo-pie-1').pieChart({
                barColor: '#3bb2d0',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function (from, to, percent) 
				{
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-2').pieChart({
                barColor: '#fbb03b',
                trackColor: '#eee',
                lineCap: 'butt',
                lineWidth: 8,
                onStep: function (from, to, percent) 
				{
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-3').pieChart({
                barColor: '#ed6498',
                trackColor: '#eee',
                lineCap: 'square',
                lineWidth: 8,
                onStep: function (from, to, percent) 
				{
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-4').pieChart({
                barColor: '#3bb2d0',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function (from, to, percent) 
				{
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-5').pieChart({
                barColor: '#fbb03b',
                trackColor: '#eee',
                lineCap: 'butt',
                lineWidth: 8,
                onStep: function (from, to, percent) 
				{
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

            $('#demo-pie-6').pieChart({
                barColor: '#ed6498',
                trackColor: '#eee',
                lineCap: 'square',
                lineWidth: 8,
                onStep: function (from, to, percent) 
				{
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });
           
        });

    </script>
<!--skycons-icons-->
<script src="js/skycons.js"></script>
<!--//skycons-icons-->
</head>
<body>
<div id="wrapper">

<!----->
        <nav class="navbar-default navbar-static-top" role="navigation">
             <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <h1> <a class="navbar-brand" href="index.php">Dashboards</a></h1>         
			   </div>
			 <div class=" border-bottom">
        	<div class="full-left">
        	  <section class="full-top">
				<button id="toggle"><i class="fa fa-arrows-alt"></i></button>	
			</section>
            <div class="clearfix"> </div>
           </div>    
       
            <!-- Brand and toggle get grouped for better mobile display -->
		 
		   <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="drop-men" >
		        <ul class=" nav_1">		           
		    		<li class="dropdown at-drop">
		              <a href="#" class="dropdown-toggle dropdown-at " data-toggle="dropdown"><i class="fa fa-globe"></i> <span class="number">5</span></a>
		              <ul class="dropdown-menu menu1 " role="menu">
		                <li><a href="#">
		               
		                	<div class="user-new">
		                	<p>New user registered</p>
		                	<span>40 seconds ago</span>
		                	</div>
		                	<div class="user-new-left">
		                
		                	<i class="fa fa-user-plus"></i>
		                	</div>
		                	<div class="clearfix"> </div>
		                	</a></li>
		                <li><a href="#">
		                	<div class="user-new">
		                	<p>Someone special liked this</p>
		                	<span>3 minutes ago</span>
		                	</div>
		                	<div class="user-new-left">
		                
		                	<i class="fa fa-heart"></i>
		                	</div>
		                	<div class="clearfix"> </div>
		                </a></li>
		                <li><a href="#">
		                	<div class="user-new">
		                	<p>John cancelled the event</p>
		                	<span>4 hours ago</span>
		                	</div>
		                	<div class="user-new-left">
		                
		                	<i class="fa fa-times"></i>
		                	</div>
		                	<div class="clearfix"> </div>
		                </a></li>
		                <li><a href="#">
		                	<div class="user-new">
		                	<p>The server is status is stable</p>
		                	<span>yesterday at 08:30am</span>
		                	</div>
		                	<div class="user-new-left">
		                
		                	<i class="fa fa-info"></i>
		                	</div>
		                	<div class="clearfix"> </div>
		                </a></li>
		                <li><a href="#">
		                	<div class="user-new">
		                	<p>New comments waiting approval</p>
		                	<span>Last Week</span>
		                	</div>
		                	<div class="user-new-left">
		                
		                	<i class="fa fa-rss"></i>
		                	</div>
		                	<div class="clearfix"> </div>
		                </a></li>
		                <li><a href="#" class="view">View all messages</a></li>
		              </ul>
		            </li>
					<li class="dropdown">
		              <a href="#" class="dropdown-toggle dropdown-at" data-toggle="dropdown"><span class=" name-caret">Rackham<i class="caret"></i></span><img src="images/wo.jpg"></a>
		              <ul class="dropdown-menu " role="menu">
		                <li><a href="profile.html"><i class="fa fa-user"></i>Edit Profile</a></li>
		                <li><a href="inbox.html"><i class="fa fa-envelope"></i>Inbox</a></li>
		                <li><a href="calendar.html"><i class="fa fa-calendar"></i>Calender</a></li>
		                <li><a href="inbox.html"><i class="fa fa-clipboard"></i>Tasks</a></li>
		              </ul>
		            </li>
		           
		        </ul>
		     </div><!-- /.navbar-collapse -->
			<div class="clearfix">
       
     </div>
	  
		    
        </nav>
        <div id="page-wrapper1" class="gray-bg dashbard-1">
       <div class="content-main">
 
		<!--content-->
		<div class="content-top">	
			
			<div class="col-md-3 ">
				<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Permits</h5>
					<label><?= $Values['actual'][1]; ?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-1" class="pie-title-center" data-percent="<?= $Values['percentage'][1]; ?>"> <span class="pie-value"></span> </div>
				</div>
				 <div class="clearfix"> </div>
				</div>
				<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Parking</h5>
					<label><?= $Values['actual'][2]; ?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-2" class="pie-title-center" data-percent="<?= $Values['percentage'][2]; ?>"> <span class="pie-value"></span> </div>
				</div>
				 <div class="clearfix"> </div>
				</div>
				<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Health</h5>
					<label><?= $Values['actual'][3]; ?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-3" class="pie-title-center" data-percent="<?= $Values['percentage'][3]; ?>"> <span class="pie-value"></span> </div>
				</div>
				 <div class="clearfix"> </div>
				</div>
			</div>
			<div class="col-md-3 ">
				<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Adverts</h5>
					<label><?= $Values['actual'][4]; ?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-4" class="pie-title-center" data-percent="<?= $Values['percentage'][4]; ?>"> <span class="pie-value"></span> </div>
				</div>
				 <div class="clearfix"> </div>
				</div>
				<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Land Rates</h5>
					<label><?= $Values['actual'][5]; ?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-5" class="pie-title-center" data-percent="<?= $Values['percentage'][5]; ?>"> <span class="pie-value"></span> </div>
				</div>
				 <div class="clearfix"> </div>
				</div>
				<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Transport</h5>
					<label><?= $Values['actual'][6]; ?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-6" class="pie-title-center" data-percent="<?= $Values['percentage'][6]; ?>"> <span class="pie-value"></span> </div>
				</div>
				 <div class="clearfix"> </div>
				</div>
			</div>            
			<div class="col-md-6 content-top-2">
				<!---start-chart---->
				<!----->
				<div class="content-graph">
				<div class="content-color">
					<div class="content-ch"><p><i></i>Target </p><span>100%</span>
					<div class="clearfix"> </div>
					</div>
					<div class="content-ch1"><p><i></i>Actual</p><span> 50%</span>
					<div class="clearfix"> </div>
					</div>
				</div>
				<!--graph-->
		<link rel="stylesheet" href="css/graph.css">
		<!--//graph-->
		<script src="js/jquery.flot.js"></script>
		<script>
			$(document).ready(function () 
			{
				var targetdata = <?= $TargetData; ?>;
				var actualdata = <?= $ActualData; ?>;
				// Graph Data ##############################################
				var graphData = [
				{
					// Target
					data: targetdata,
					color: '#999999'
				}, 
				{
					// Actual
					data: actualdata,
					color: '#999999',
					points: { radius: 4, fillColor: '#7f8c8d' }
				}
				];
									
										// Lines Graph #############################################
										$.plot($('#graph-lines'), graphData, {
											series: {
												points: {
													show: true,
													radius: 5
												},
												lines: {
													show: true
												},
												shadowSize: 0
											},
											grid: {
												color: '#7f8c8d',
												borderColor: 'transparent',
												borderWidth: 20,
												hoverable: true
											},
											xaxis: {
												tickColor: 'transparent',
												tickDecimals: 2
											},
											yaxis: {
												tickSize: 1000
											}
										});
									
										// Bars Graph ##############################################
										$.plot($('#graph-bars'), graphData, {
											series: {
												bars: {
													show: true,
													barWidth: .9,
													align: 'center'
												},
												shadowSize: 0
											},
											grid: {
												color: '#7f8c8d',
												borderColor: 'transparent',
												borderWidth: 20,
												hoverable: true
											},
											xaxis: {
												tickColor: 'transparent',
												tickDecimals: 2
											},
											yaxis: {
												tickSize: 1000
											}
										});
									
										// Graph Toggle ############################################
										$('#graph-bars').hide();
									
										$('#lines').on('click', function (e) {
											$('#bars').removeClass('active');
											$('#graph-bars').fadeOut();
											$(this).addClass('active');
											$('#graph-lines').fadeIn();
											e.preventDefault();
										});
									
										$('#bars').on('click', function (e) {
											$('#lines').removeClass('active');
											$('#graph-lines').fadeOut();
											$(this).addClass('active');
											$('#graph-bars').fadeIn().removeClass('hidden');
											e.preventDefault();
										});
									
										// Tooltip #################################################
										function showTooltip(x, y, contents) {
											$('<div id="tooltip">' + contents + '</div>').css({
												top: y - 16,
												left: x + 20
											}).appendTo('body').fadeIn();
										}
									
										var previousPoint = null;
									
										$('#graph-lines, #graph-bars').bind('plothover', function (event, pos, item) {
											if (item) {
												if (previousPoint != item.dataIndex) {
													previousPoint = item.dataIndex;
													$('#tooltip').remove();
													var x = item.datapoint[0],
														y = item.datapoint[1];
														showTooltip(item.pageX, item.pageY, y + ' visitors at ' + x + '.00h');
												}
											} else {
												$('#tooltip').remove();
												previousPoint = null;
											}
										});
									
									});
									</script>
				<div class="graph-container">
									
									<div id="graph-lines"> </div>
									<div id="graph-bars"> </div>
								</div>
	
		</div>
		</div>
		<div class="clearfix"> </div>
		</div>
		<!---->
	
		<!----->
	
		<!--//content-->
 
		<!---->
<div class="copy">
            <p> ...</p>
	    </div>
		</div>
		<div class="clearfix"> </div>
       </div>
     </div>
<!---->
<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<script src="js/bootstrap.min.js"> </script>
</body>
</html>

