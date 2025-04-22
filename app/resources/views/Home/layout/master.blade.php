<!DOCTYPE html>
<html lang="en">
<head>
	@yield('meta')
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{ asset('images/icons/favicon.png') }}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('fonts/iconic/css/material-design-iconic-font.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('fonts/linearicons-v1.0.0/icon-font.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/animate/animate.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/select2/select2.min.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/slick/slick.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/MagnificPopup/magnific-popup.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
<!--===============================================================================================-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

</head>
<body class="animsition">
	
		<!-- Header -->
		<header>
			<div class="container-menu-desktop">
				<div class="top-bar">
					<div class="content-topbar flex-sb-m h-full container">
						<div class="left-top-bar">
							Free shipping for standard order over $100
						</div>
						<div class="right-top-bar flex-w h-full">
    
							<!-- روابط الدعم واللغة والعملة -->
							<a href="{{ route('help') }}" class="flex-c-m trans-04 p-lr-25">Help & FAQs</a>
							<a href="#" class="flex-c-m trans-04 p-lr-25">EN</a>
							<a href="#" class="flex-c-m trans-04 p-lr-25">USD</a>
							 <!-- أيقونة البحث --> 
							<div class="wrap-icon-header flex-w flex-r-m">
								<a href="{{ route('product.searchForm') }}" 
								   class="flex-c-m trans-04 p-lr-25" 
								   style="font-size: 22px; padding: 10px; display: flex; align-items: center; justify-content: center;">
									<i class="zmdi zmdi-search"></i>
								</a>
							</div>
							
						</div>
						
					</div>
				</div>
				<div class="wrap-menu-desktop">
					<nav class="limiter-menu-desktop container">
						<!-- الشعار -->
						<a href="#" class="logo">
							<img src="{{ asset('images/shama2.JPG') }}" alt="IMG-LOGO">
						</a>
				
						<!-- القائمة الرئيسية وحساب المستخدم -->
						<div class="menu-desktop" style="display: flex; align-items: center; width: 100%;">
							<!-- القائمة الرئيسية -->
							<ul class="main-menu" style="display: flex; gap: 20px;">
								<li class="{{ Request::is('/') ? 'active-menu' : '' }}"><a href="/">Home</a></li>
								<li  class="{{ Request::is('userproducts.index') ? 'active-menu' : '' }}"><a href="{{ route('userproducts.index')}}">Shop</a></li>
								<li class="{{ Request::is('AR_Products.index') ? 'active-menu' : '' }}"><a href="{{ route('AR_Products.index') }}">AR</a></li>
								<li class="{{ Request::is('blog') ? 'active-menu' : '' }}"><a href="{{ route('blog') }}">Blog</a></li>
								<li class="{{ Request::is('about') ? 'active-menu' : '' }}"><a href="{{ route('about') }}">About</a></li>
								<li  class="{{ Request::is('contact') ? 'active-menu' : '' }}"><a href="{{ route('contact') }}">Contact</a></li>
								@if(Auth::check())
									<li class="{{ Request::is('customer/dashboard') ? 'active-menu' : '' }}"><a class="dropdown-item" href="{{ route('customer.dashboard') }}">Dashboard</a></li>
								@endif
							</ul>
							
							<!-- حساب المستخدم في أقصى اليمين -->
							<div class="user-menu flex-w flex-r-m" style="margin-left: auto;">
								@auth
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											{{ Auth::user()->name }}
										</button>
										<div class="dropdown-menu" aria-labelledby="userDropdown">
											<a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
											<form method="POST" action="{{ route('logout') }}">
												@csrf
												<button type="submit" class="dropdown-item">Log Out</button>
											</form>
										</div>
									</div>
								@else
									<a href="{{ route('login') }}" class="flex-c-m trans-04 p-lr-25">Login</a>
									<a href="{{ route('register') }}" class="flex-c-m trans-04 p-lr-25">Register</a>
								@endauth
							</div>
						</div>
					</nav>
				</div>
				
			</div>
		</header>


		<!-- Header Mobile -->
		<div class="wrap-header-mobile">
			<!-- Logo moblie -->
			<div class="logo-mobile">
				<a href="/"><img src="{{ asset('images/shama2.JPG') }}" alt="IMG-LOGO"></a>

				<div class="user-menu flex-w flex-r-m" style="margin-left: auto;">
					@auth
						<div class="dropdown">
							<button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								{{ Auth::user()->name }}
							</button>
							<div class="dropdown-menu" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
								<form method="POST" action="{{ route('logout') }}">
									@csrf
									<button type="submit" class="dropdown-item">Log Out</button>
								</form>
							</div>
						</div>
					@else
						<a href="{{ route('login') }}" class="flex-c-m trans-04 p-lr-25">Login</a>
						<a href="{{ route('register') }}" class="flex-c-m trans-04 p-lr-25">Register</a>
					@endauth
				</div>
			</div>

			<!-- Button show menu -->
			<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</div>
		</div>

		<!-- Menu Mobile -->
		<div class="menu-mobile">
			<ul class="topbar-mobile">
				<li>
					<div class="left-top-bar">
						Free shipping for standard order over $100
					</div>
				</li>
				<li>
					<div class="right-top-bar flex-w h-full">
						<a href="{{ route('help') }}" class="flex-c-m p-lr-10 trans-04">
							Help & FAQs
						</a>
						
						<a href="#" class="flex-c-m p-lr-10 trans-04">
							EN
						</a>
						<a href="#" class="flex-c-m p-lr-10 trans-04">
							USD
						</a>
						<a href="{{ route('product.searchForm') }}" class="flex-c-m p-lr-10 trans-04"
						style="font-size: 22px; display: flex; ">
							<i class="zmdi zmdi-search"></i>
						</a>
							
						
					</div>
				</li>
			</ul>

			<ul class="main-menu-m">
				<li>
					<a href="/">Home</a>
					<span class="arrow-main-menu-m">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</li>
				<li>
					<a href="{{ route('userproducts.index') }}">Shop</a>
				</li>
				<li>
					<a href="{{ route('AR_Products.index') }}">AR</a>
				</li>
				<li>
					<a href="{{ route('about') }}">About</a>
				</li>
				<li>
					<a href="{{ route('contact') }}">Contact</a>
				</li>
				@auth
					<li>
						<a href="{{ route('customer.dashboard') }}">
							Dashboard
						</a>
					</li>
				@else
					<li>
						<a href="{{ route('login') }}">Login</a>
					</li>
					<li>
						<a href="{{ route('register') }}">Register</a>
					</li>
				@endauth
			</ul>
		</div>

	</header>



	@yield('content')



	<!-- Footer -->
	<footer class="bg3 p-t-75 p-b-32">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Categories
					</h4>

					<ul>
						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Women
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Men
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Shoes
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Watches
							</a>
						</li>
					</ul>
				</div>

				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Help
					</h4>

					<ul>
						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Track Order
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Returns 
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Shipping
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								FAQs
							</a>
						</li>
					</ul>
				</div>

				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						GET IN TOUCH
					</h4>

					<p class="stext-107 cl7 size-201">
						Any questions? Let us know in store at 8th floor, 379 Hudson St, New York, NY 10018 or call us on (+1) 96 716 6879
					</p>

					<div class="p-t-27">
						<a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
							<i class="fa fa-facebook"></i>
						</a>

						<a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
							<i class="fa fa-instagram"></i>
						</a>

						<a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
							<i class="fa fa-pinterest-p"></i>
						</a>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Newsletter
					</h4>

					<form>
						<div class="wrap-input1 w-full p-b-4">
							<input class="input1 bg-none plh1 stext-107 cl7" type="text" name="email" placeholder="email@example.com">
							<div class="focus-input1 trans-04"></div>
						</div>

						<div class="p-t-18">
							<button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn2 p-lr-15 trans-04">
								Subscribe
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="p-t-40">
				<div class="flex-c-m flex-w p-b-18">
					<a href="#" class="m-all-1">
						<img src="{{ asset('images/icons/icon-pay-01.png')}}" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="{{ asset('images/icons/icon-pay-02.png')}}" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="{{ asset('images/icons/icon-pay-03.png')}}" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="{{ asset('images/icons/icon-pay-04.png')}}" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="{{ asset('images/icons/icon-pay-05.png')}}" alt="ICON-PAY">
					</a>
				</div>

				<p class="stext-107 cl6 txt-center">
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
	Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a> &amp; distributed by <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
	<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->

				</p>
			</div>
		</div>
	</footer>


<!--===============================================================================================-->	
	<script type="text/javascript" src="{{ asset('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset('vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset('vendor/bootstrap/js/popper.js') }}"></script>
	<script type="text/javascript" src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset('vendor/select2/select2.min.js') }}"></script>
	<script>
		$(".js-select2").each(function(){
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		})
	</script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset('vendor/daterangepicker/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>

<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset('vendor/slick/slick.min.js') }}"></script>
	<script src="js/slick-custom.js"></script>
<!--===============================================================================================-->
	<script src="vendor/parallax100/parallax100.js"></script>
	<script>
        $('.parallax100').parallax100();
	</script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset('vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
	<script>
		$('.gallery-lb').each(function() { // the containers for all your galleries
			$(this).magnificPopup({
		        delegate: 'a', // the selector for gallery item
		        type: 'image',
		        gallery: {
		        	enabled:true
		        },
		        mainClass: 'mfp-fade'
		    });
		});
	</script>
<!--===============================================================================================-->
	<script src="vendor/isotope/isotope.pkgd.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/sweetalert/sweetalert.min.js"></script>
	<script>
		$('.js-addwish-b2').on('click', function(e){
			e.preventDefault();
		});

		$('.js-addwish-b2').each(function(){
			var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
			$(this).on('click', function(){
				swal(nameProduct, "is added to wishlist !", "success");

				$(this).addClass('js-addedwish-b2');
				$(this).off('click');
			});
		});

		$('.js-addwish-detail').each(function(){
			var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

			$(this).on('click', function(){
				swal(nameProduct, "is added to wishlist !", "success");

				$(this).addClass('js-addedwish-detail');
				$(this).off('click');
			});
		});

		/*---------------------------------------------*/

		$('.js-addcart-detail').each(function(){
			var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
			$(this).on('click', function(){
				swal(nameProduct, "is added to cart !", "success");
			});
		});
	
	</script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
	<script>
		$('.js-pscroll').each(function(){
			$(this).css('position','relative');
			$(this).css('overflow','hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});

			$(window).on('resize', function(){
				ps.update();
			})
		});
	</script>
<!--===============================================================================================-->
	<script src="{{ asset('js/main.js') }}"></script>

</body>
</html>

