@extends('layouts.app')

@section('title', 'Bless-Tech Shop Page')

@section('sidebar')    
    <p>This is appended to the master sidebar.</p>
@stop

@section('content')
<div id="information-contact" class="container">
  <ul class="breadcrumb">
        <li><a href="http://uren2.demo.towerthemes.com/"><i class="fa fa-home"></i></a></li>
        <li><a href="http://uren2.demo.towerthemes.com/information-contact">Contact Us</a></li>
      </ul>
  <div class="row">
                <div id="content" class="col-sm-12">
      <h1>Contact Us</h1>
      <h3>Our Location</h3>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
                        <div class="col-sm-3"><img src="http://uren2.demo.towerthemes.com/image/cache/catalog/opencart-logo-268x50.png" alt="Store 2" title="Store 2" class="img-thumbnail" /></div>
                        <div class="col-sm-3"><strong>Store 2</strong><br />
              <address>
              Channel 1
              </address>
                            <a href="https://maps.google.com/maps?q=41.6078817%2C-93.6958388&amp;hl=en-gb&amp;t=m&amp;z=15" target="_blank" class="btn btn-info"><i class="fa fa-map-marker"></i> View Google Map</a>
                          </div>
            <div class="col-sm-3"><strong>Telephone</strong><br>
              +263782954717<br />
              <br />
                            <strong>Fax</strong><br>
              +263782954717
                          </div>
            <div class="col-sm-3">
                            <strong>Opening Times</strong><br />
              8:00 AM - 8:00 PM<br />
              <br />
                                          <strong>Comments</strong><br />
              Welcome to our online store!
                          </div>
          </div>
        </div>
      </div>
            <form action="http://uren2.demo.towerthemes.com/information-contact" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend>Contact Form</legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name">Your Name</label>
            <div class="col-sm-10">
              <input type="text" name="name" value="" id="input-name" class="form-control" />
                          </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email">E-Mail Address</label>
            <div class="col-sm-10">
              <input type="text" name="email" value="" id="input-email" class="form-control" />
                          </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-enquiry">Enquiry</label>
            <div class="col-sm-10">
              <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control"></textarea>
                          </div>
          </div>
          
        </fieldset>
        <div class="buttons">
          <div class="pull-right">
            <input class="btn btn-primary" type="submit" value="Submit" />
          </div>
        </div>
      </form>
      </div>
    </div>
</div>
<div class="container">
	<div class="brand-logo products-container">	
	<div class="block-title">
		            <p class="sub-title">Top Quality Partner</p>
        		<h3><span>Shop By Bless-Tech</span></h3>
	</div>
	<div class="pt-content">
		<div class="swiper-viewport">
		  <div id="carousel0" class="swiper-container">
			<div class="swiper-wrapper">			  <div class="swiper-slide text-center product-thumb"><div class="product-item"><a href="#"><img src="http://uren2.demo.towerthemes.com/image/cache/catalog/brandslider/br1-174x106.jpg" alt="NFL" class="img-responsive" /></a></div></div>
			  			  <div class="swiper-slide text-center product-thumb"><div class="product-item"><a href="#"><img src="http://uren2.demo.towerthemes.com/image/cache/catalog/brandslider/br7-174x106.jpg" alt="RedBull" class="img-responsive" /></a></div></div>
			  			  <div class="swiper-slide text-center product-thumb"><div class="product-item"><a href="#"><img src="http://uren2.demo.towerthemes.com/image/cache/catalog/brandslider/br3-174x106.jpg" alt="Sony" class="img-responsive" /></a></div></div>
			  			  <div class="swiper-slide text-center product-thumb"><div class="product-item"><a href="#"><img src="http://uren2.demo.towerthemes.com/image/cache/catalog/brandslider/br4-174x106.jpg" alt="Coca Cola" class="img-responsive" /></a></div></div>
			  			  <div class="swiper-slide text-center product-thumb"><div class="product-item"><a href="#"><img src="http://uren2.demo.towerthemes.com/image/cache/catalog/brandslider/br5-174x106.jpg" alt="Burger King" class="img-responsive" /></a></div></div>
			  			  <div class="swiper-slide text-center product-thumb"><div class="product-item"><a href="#"><img src="http://uren2.demo.towerthemes.com/image/cache/catalog/brandslider/br6-174x106.jpg" alt="Canon" class="img-responsive" /></a></div></div>
			  			  <div class="swiper-slide text-center product-thumb"><div class="product-item"><a href="#"><img src="http://uren2.demo.towerthemes.com/image/cache/catalog/brandslider/br2-174x106.jpg" alt="Harley Davidson" class="img-responsive" /></a></div></div>
			  </div>
		  </div>
		  <div class="swiper-pagination carousel0"></div>
		  <!--div class="swiper-pager">
			<div class="swiper-button-next brand-logo-next"></div>
			<div class="swiper-button-prev brand-logo-prev"></div>
		  </div-->
		</div>
	</div>
</div>
<script ><!--
$('#carousel0').swiper({
	mode: 'horizontal',
	slidesPerView: 7,
	pagination: false,
	paginationClickable: true,
	watchSlidesVisibility: true,
	nextButton: '.brand-logo-next',
    prevButton: '.brand-logo-prev',
	autoplay: false,
	loop: true,
	// Responsive breakpoints
	breakpoints: {
		479: {
		  slidesPerView: 2
		},
		767: {
		  slidesPerView: 3
		},
		991: {
		  slidesPerView: 4
		  
		},
		1199: {
		  slidesPerView: 5
		  
		}
	}
});
--></script>

</div>
@stop