<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Project</title>
    <!-- Bootstrap core CSS -->    
    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
   	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/slidePushMenus/default.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/slidePushMenus/component.css" />
	<script src="<?php echo base_url();?>js/slidePushMenus/modernizr.custom.js"></script>
	<script src="<?php echo base_url();?>js/myfunction.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/nav.css" />	
	<script src="<?php echo base_url();?>js/jquery1.11.0.min.js"></script>
</head>
<body class="cbp-spmenu-push">  
	<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
		<h4 class='menu-left'><img style='width:45px; height:45px;' src="<?php echo base_url();?>image/user.jpg"  class="img-rounded"> Username</h4>
		<a href="#">menu1</a>
		<a href="#">menu1</a>
		<a href="#">menu1</a>
		<a onclick='$("#l_sub").slideToggle("fast");' href="#">sub menu1 <span class='glyphicon glyphicon-chevron-down'></span></a>			
		<ul id='l_sub' style='display:none;'>
			<li><a href="#">sub menu1</a></li>								
			<li><a href="#">sub menu1</a></li>								
			<li><a href="#">sub menu1</a></li>															
		</ul>
		<a href="#">menu1</a> 
	</nav>
	<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">	
		<h4>Setting</h4>
		<a href="#">log out</a>
		<a href="#">menu1</a>
		<a href="#">menu1</a>
		<a onclick='$("#r_sub").slideToggle("fast");' href="#">sub menu1 <span class='glyphicon glyphicon-chevron-down'></span></a>			
		<ul id='r_sub' style='display:none;'>
			<li><a href="#">sub menu1</a></li>								
			<li><a href="#">sub menu1</a></li>								
			<li><a href="#">sub menu1</a></li>															
		</ul>
		<a href="#">menu1</a> 
	</nav>		
	<div id='nav_main' class="navbar navbar-inverse navbar-static-top" role="navigation">  
		<div class="container">							
			<a class="navbar-brand" href="#"><?php echo $str_title;?></a>
			<button id="showLeftPush"  type="button" style=''  class="navbar-toggle pull-left" > 
				<span class="sr-only">Toggle navigation</span>
				<img style="width:20px; height:14px;" src="<?php echo base_url();?>image/icon-bar.png" />
			</button>
			<button id='showRightPush' type="button" style=' display: block; margin-right:0px;padding: 6px 13px;' class="navbar-toggle pull-right" > 
				<span class="sr-only">Toggle navigation</span>
				<img style="width:20px; height:20px;" src="<?php echo base_url();?>image/glyphicon-cog.png" />
			</button>			
		</div>
	</div>
	<div class='row'>
	<div  class='hidden-xs col-sm-3 col-md-3 col-lg-2' style='float:left;height:100%;'>	
		<nav id='cbp-spmenu-s3' class="cbp-spmenu cbp-vertical">
		<h4 class='menu-left'><img style='width:45px; height:45px;' src="<?php echo base_url();?>image/user.jpg"  class="img-rounded"> Username</h4>
			<a href="#">menu1</a>
			<a href="#">menu1</a>
			<a onclick='$("#fix_sub").slideToggle("fast");' href="#">sub menu1 <span class='glyphicon glyphicon-chevron-down'></span></a>			
			<ul id='fix_sub' style='display:none;'>
				<li><a href="#">sub menu1</a></li>								
				<li><a href="#">sub menu1</a></li>								
				<li><a href="#">sub menu1</a></li>																
			</ul>
		</nav>
	</div>