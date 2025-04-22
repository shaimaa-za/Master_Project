@extends('Home.layout.master')
@section('meta')
    <title>Blog - Luxury Jewelry</title>
    <meta name="description" content="Explore our blog for expert insights, the latest trends, and practical tips on luxury jewelry. Stay informed about design inspirations, market analysis, and more.">
    <meta name="keywords" content="luxury jewelry, blog, latest high jewellery collection, design inspiration, market analysis, jewelry insights">
@endsection

@section('content')
	<!-- Title page -->
	<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('images/sasa.jpg');">
		<h2 class="ltext-105 cl0 txt-center">
			Blog
		</h2>
	</section>	


	<!-- Content page -->
	<section class="bg0 p-t-62 p-b-60">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-lg-9 p-b-80">
					<div class="p-r-45 p-r-0-lg">


						<!-- item blog -->
						<div class="p-b-63">
							<a href="blog-detail.html" class="hov-img0 how-pos5-parent">
								<img src="images/reesh.png" alt="IMG-BLOG">

								<div class="flex-col-c-m size-123 bg9 how-pos5">
									<span class="ltext-107 cl2 txt-center">
										18
									</span>

									<span class="stext-109 cl3 txt-center">
										Jan 2025
									</span>
								</div>
							</a>

							<div class="p-t-32">
								<h4 class="p-b-15">
									<a href="blog-detail.html" class="ltext-108 cl2 hov-cl1 trans-04">
										Plume de Chanel brings a lightness of touch to high jewellery Light-as-air feathers dominate Chanel’s 2025 Plume de Chanel high jewellery collection. 
									</a>
								</h4>

								<p class="stext-117 cl6">
									Chanel turns to Gabrielle Chanel's early diamond jewellery design of a feather for its latest high jewellery collection. Feathers have featured consistently in the designer's work. As early as 1910, Gabrielle Chanel incorporated feathers into her creations when she added a single outsized, billowing feather to a large-brimmed hat. Before venturing into couture, Mademoiselle Chanel’s first business was millinery, and it was here that she became fascinated with feathers. 
                                </p>

								<div class="flex-w flex-sb-m p-t-18">
									<span class="flex-w flex-m stext-111 cl2 p-r-30 m-tb-10">
										<span>
											<span class="cl4">By</span> Admin  
											<span class="cl12 m-l-4 m-r-6">|</span>
										</span>

										<span>
											StreetStyle, Fashion, Couple  
											<span class="cl12 m-l-4 m-r-6">|</span>
										</span>

										<span>
											8 Comments
										</span>
									</span>

									<a href="{{ route('blog_Detail') }}" class="stext-101 cl2 hov-cl1 trans-04 m-tb-10">
                                        Continue Reading
                                        <i class="fa fa-long-arrow-right m-l-9"></i>
                                    </a>
                                    
								</div>
							</div>
						</div>

						<!-- item blog -->
						<div class="p-b-63">
							<a href="blog-detail.html" class="hov-img0 how-pos5-parent">
								<img src="images/cof.jpg" alt="IMG-BLOG">

								<div class="flex-col-c-m size-123 bg9 how-pos5">
									<span class="ltext-107 cl2 txt-center">
										16
									</span>

									<span class="stext-109 cl3 txt-center">
										Jan 2025
									</span>
								</div>
							</a>

							<div class="p-t-32">
								<h4 class="p-b-15">
									<a href="blog-detail.html" class="ltext-108 cl2 hov-cl1 trans-04">
										The Autumn Palette
As nature slowly transitions from summer to the warm, golden tones of autumn so does jewellery as was richly in evidence at the PAD Art Fair. 
									</a>
								</h4>

								<p class="stext-117 cl6">
                                    Citrines are the birthstone of November, a colour so warmly replicated in nature as the trees take on their golden autumn mantle. It is a colour and a gemstone that featured strongly in jewellery collections shown at PAD the Design and Decorative Arts Fair in October as were many other warm toned gemstones. The glowing colours of amber, spessartite and mandarin, the darker coloured spessartite garnet, brown tourmalines and zircons, cognac diamonds and more are not a palette we see too often but have inspired designers anew with exciting results.
                                </p>
                                <p class="stext-117 cl6">   
                                    <a href=" https://hemmerle.com/" target="_blank" rel="noopener noreferrer">Hemmerle</a> presented polished toffee-coloured antique amber beads that are wonderfully tactile and pairs of citrine or brown zircon earrings, the latter paired with satin and olive wood settings. Boghossian’s signature flame-like silhouette set alight the pair of Golden Ember amber earrings from their Palace Voyages collection. 								</p>

								<div class="flex-w flex-sb-m p-t-18">
									<span class="flex-w flex-m stext-111 cl2 p-r-30 m-tb-10">
										<span>
											<span class="cl4">By</span> Admin  
											<span class="cl12 m-l-4 m-r-6">|</span>
										</span>

										<span>
											StreetStyle, Fashion, Couple  
											<span class="cl12 m-l-4 m-r-6">|</span>
										</span>

										<span>
											8 Comments
										</span>
									</span>

									<a href="{{ route('blog_Detail') }}" class="stext-101 cl2 hov-cl1 trans-04 m-tb-10">
										Continue Reading

										<i class="fa fa-long-arrow-right m-l-9"></i>
									</a>
								</div>
							</div>
						</div>

						<!-- Pagination -->
						<div class="flex-l-m flex-w w-full p-t-10 m-lr--7">
							<a href="#" class="flex-c-m how-pagination1 trans-04 m-all-7 active-pagination1">
								1
							</a>

							<a href="#" class="flex-c-m how-pagination1 trans-04 m-all-7">
								2
							</a>
						</div>
					</div>
				</div>

				<div class="col-md-4 col-lg-3 p-b-80">
					<div class="side-menu">
						<div class="bor17 of-hidden pos-relative">
							<input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" name="search" placeholder="Search">

							<button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
								<i class="zmdi zmdi-search"></i>
							</button>
						</div>

						<div class="p-t-55">
							<h4 class="mtext-112 cl2 p-b-33">
								Categories
							</h4>

							<ul>
								<li class="bor18">
									<a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
										Gold
									</a>
								</li>

								<li class="bor18">
									<a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
										Silver
									</a>
								</li>

								<li class="bor18">
									<a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        Precious Stones
									</a>
								</li>

								<li class="bor18">
									<a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        Luxury Watches
									</a>
								</li>
							</ul>
						</div>

						
					</div>
				</div>
			</div>
		</div>
	</section>	
	

@endsection