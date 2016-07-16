<!DOCTYPE html>
<html>
	<head>

		
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo $ptitle ?></title>
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/theme/images/favicon.ico"/>

		<!-- GOOGLE FONTS : begin -->
		<link href="../../fonts.googleapis.com/css_9fb789b0.css" rel="stylesheet" type="text/css" />
		<!-- GOOGLE FONTS : end -->

        <!-- STYLESHEETS : begin -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/casa/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/casa/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/casa/css/owl.carousel.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/casa/css/animate.custom.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/casa/css/magnific-popup.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/casa/css/style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/casa/css/skins/default.css" />
		<!--link rel="stylesheet" type="text/css" href="library/css/custom.css"> uncomment if you want to use custom CSS definitions -->
		<!-- STYLESHEETS : end -->

        <!--[if lte IE 8]>
			<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/casa/css/oldie.css" />
			<script src="<?php echo base_url(); ?>assets/casa/js/respond.min.js" type="text/javascript"></script>
        <![endif]-->
		<script src="<?php echo base_url(); ?>assets/casa/js/modernizr.custom.min.js" type="text/javascript"></script>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
	<body class="enable-fixed-header enable-style-switcher enable-inview-animations">

		<!-- HEADER : begin -->
		<header id="header">
			<div class="container">
				<div class="header-inner clearfix">

					<!-- HEADER BRANDING : begin -->
					<div class="header-branding">
						<a href="<?php echo site_url(); ?>">
                    		<img src="<?php echo base_url() ?>assets/theme/images/logo.png">
                		</a>
					</div>
					<!-- HEADER BRANDING : end -->

					<!-- HEADER NAVBAR : begin -->
					<div class="header-navbar">

						<!-- HEADER SEARCH : begin -->
						<div class="header-search">
							<form class="default-form" action="index.html" />

								<!-- SEARCH INPUT : begin -->
								<div class="search-input">
									<i class="ico fa fa-search"></i>
									<i class="close fa fa-times"></i>
									<input type="text" />
								</div>
								<!-- SEARCH INPUT : end -->

								<!-- SEARCH ADVANCED : begin -->
								<div class="header-form search-advanced">
									<div class="search-advanced-inner">

										<!-- ARRIVAL DATE, DEPARTURE DATE : begin -->
										<p class="form-row clearfix">
											<span class="calendar-input input-left" title="Arrival">
												<input type="text" name="arrival" placeholder="Arrival" data-dateformat="m/d/y" />
												<i class="fa fa-calendar"></i>
											</span>
											<span class="calendar-input input-right" title="Departure">
												<input type="text" name="departure" placeholder="Departure" data-dateformat="m/d/y" />
												<i class="fa fa-calendar"></i>
											</span>
										</p>
										<!-- ARRIVAL DATE, DEPARTURE DATE : end -->

										<!-- NUMBER OF ADULTS AN CHILDREN : begin -->
										<p class="form-row clearfix">
											<span class="select-box input-left" title="Adults">
												<select name="adults" data-placeholder="Adults">
													<option />Adults
													<option value="1" />1
													<option value="2" />2
													<option value="3" />3
													<option value="4" />4
													<option value="5" />5
												</select>
											</span>
											<span class="select-box input-right" title="Children">
												<select name="children" data-placeholder="Children">
													<option />Children
													<option value="1" />1
													<option value="2" />2
													<option value="3" />3
													<option value="4" />4
													<option value="5" />5
												</select>
											</span>
										</p>
										<!-- NUMBER OF ADULTS AN CHILDREN : end -->

										<!-- TYPE : begin -->
										<p class="form-row textalign-center radio-inputs">
											<span class="radio-input">
												<input id="type_swap" type="radio" name="type" value="swap" checked="checked" />
												<label for="type_swap">Swap</label>
											</span>
											<span class="radio-input">
												<input id="type_book" type="radio" name="type" value="book" />
												<label for="type_book">Book</label>
											</span>
											<span class="radio-input">
												<input id="type_rent" type="radio" name="type" value="rent" />
												<label for="type_rent">Rent</label>
											</span>
										</p>
										<!-- TYPE : end -->

										<hr class="form-divider" />
										<p class="form-row">
											<button class="submit-btn button"><i class="fa fa-search"></i> Search</button>
										</p>

									</div>
								</div>
								<!-- SEARCH ADVANCED : end -->

							</form>
						</div>
						<!-- HEADER SEARCH : end -->

						<!-- HEADER MENU : begin -->
						<div class="header-menu">
							<button class="header-btn">Menu <i class="fa fa-angle-down"></i></button>
							<nav class="header-nav">
								<ul>
									<li class="has-submenu">
										<a href="./index.html">Home</a>
										<ul class="sub-menu">
											<li><a href="./home-2.html">Home 2</a></li>
											<li><a href="./home-3.html">Home 3</a></li>
											<li class="has-submenu">
												<a href="./index.html">Dummy Item</a>
												<ul class="sub-menu">
													<li><a href="./index.html">Dummy 1</a></li>
													<li><a href="./index.html">Dummy 2</a></li>
												</ul>
											</li>
										</ul>
									</li>
									<li class="has-submenu">
										<a href="./properties-listing-grid.html">Property Listing</a>
										<ul class="sub-menu">
											<li><a href="./properties-listing-grid.html">Grid Layout</a></li>
											<li><a href="./properties-listing-list.html">List Layout</a></li>
										</ul>
									</li>
									<li class="has-submenu">
										<a href="./property-details-swap.html">Property Detail</a>
										<ul class="sub-menu">
											<li><a href="./property-details-swap.html">Detail (Swap)</a></li>
											<li><a href="./property-details-book.html">Detail (Book)</a></li>
											<li><a href="./property-details-rent.html">Detail (Rent)</a></li>
										</ul>
									</li>
									<li><a href="./shortcodes.html">Shortcodes</a></li>
									<li><a href="./about-us.html">About Us</a></li>
									<li><a href="./contact-us.html">Contact Us</a></li>
									<li><a href="./privacy-policy.html">Privacy Policy</a></li>
									<li><a href="./terms-conditions.html">Terms &amp; Conditions</a></li>
								</ul>
							</nav>
						</div>
						<!-- HEADER MENU : end -->

						<!-- HEADER TOOLS : begin -->
						<div class="header-tools">

							<!-- HEADER LANGUAGE : begin -->
							<div class="header-language">
								<button class="header-btn">EN <i class="fa fa-angle-down"></i></button>
								<nav class="header-nav">
									<ul class="custom-list">
										<li class="active"><a href="#">EN</a></li>
										<li><a href="#">DE</a></li>
										<li><a href="#">FR</a></li>
										<li><a href="#">IT</a></li>
									</ul>
								</nav>
							</div>
							<!-- HEADER LANGUAGE : end -->

							<!-- HEADER REGISTER : begin -->
							<div class="header-register">
								<button class="register-toggle header-btn"><i class="fa fa-plus-circle"></i> Register</button>
								<div class="header-form">
									<form action="index.html" class="default-form" />
										<p class="alert-message warning"><i class="ico fa fa-exclamation-circle"></i> All fields are required! <i class="fa fa-times close"></i></p>
										<p class="form-row">
											<input class="required" type="text" placeholder="Username" />
										</p>
										<p class="form-row">
											<input class="required email" type="text" placeholder="Email" />
										</p>
										<p class="form-row">
											<input class="required" type="password" placeholder="Password" />
										</p>
										<p class="form-row">
											<input class="required" type="password" placeholder="Repeat Password" />
										</p>
										<p class="form-row">
											<button class="submit-btn button"><i class="fa fa-plus-circle"></i> Register</button>
										</p>
									</form>
								</div>
							</div>
							<!-- HEADER REGISTER : end -->

							<!-- HEADER LOGIN : begin -->
							<div class="header-login">
								<button class="login-toggle header-btn"><i class="fa fa-power-off"></i> Login</button>
								<div class="header-form">
									<form action="index.html" class="default-form" />
										<p class="alert-message warning"><i class="ico fa fa-exclamation-circle"></i> All fields are required! <i class="fa fa-times close"></i></p>
										<p class="form-row">
											<input class="required email" type="text" placeholder="Email" />
										</p>
										<p class="form-row">
											<input class="required" type="password" placeholder="Password" />
										</p>
										<p class="form-row">
											<button class="submit-btn button"><i class="fa fa-power-off"></i> Login</button>
										</p>
										<p class="form-row forgot-password">
											<a href="#">Forgot Password?</a>
										</p>
									</form>
								</div>
							</div>
							<!-- HEADER LOGIN : end -->

							<!-- HEADER ADD OFFER : begin -->
							<span class="header-add-offer"><a href="#" class="button"><i class="fa fa-plus"></i> Add Offer</a></span>
							<!-- HEADER ADD OFFER : end -->

						</div>
						<!-- HEADER TOOLS : end -->

					</div>
					<!-- HEADER NAVBAR : end -->

					<!-- SEARCH TOGGLE : begin -->
					<button class="search-toggle button"><i class="fa fa-search"></i></button>
					<!-- SEARCH TOGGLE : end -->

					<!-- NAVBAR TOGGLE : begin -->
					<button class="navbar-toggle button"><i class="fa fa-bars"></i></button>
					<!-- NAVBAR TOGGLE : end -->

				</div>
			</div>
		</header>
		<!-- HEADER : end -->