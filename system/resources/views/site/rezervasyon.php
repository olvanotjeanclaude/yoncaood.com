<!-- resources/views/welcome.blade.php -->

@extends('site.layouts.app')

@section('title', 'Anasayfa')

@section('content')
    
	
	<div id="content">
    <section class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="bg-white shadow-md rounded p-3 p-sm-4 confirm-details">
            <h2 class="text-6 mb-3 mb-sm-4">Confirm Hotels Details</h2>
            <hr class="mx-n3 mx-sm-n4 mb-4">
            <div class="row">
              <div class="col-12 col-md-3 mb-3 mb-md-0"> <img class="img-fluid rounded align-top" src="images/brands/hotels/hotel-1.jpg" title="Standard Room" alt="Standard Room"> </div>
              <div class="col-12 col-md-7">
                <h4 class="text-5">The Orchid Hotel</h4>
                <p class="text-muted mb-1"><i class="fas fa-map-marker-alt"></i> Ashram Road, Ahmedabad, Gujarat, India.</p>
                <ul class="list-inline text-muted mb-2">
                  <li class="list-inline-item"><span class="me-1 text-black-50"><i class="fas fa-home"></i></span> Deluxe Room</li>
                  <li class="list-inline-item"><span class="me-1 text-black-50"><i class="fas fa-user"></i></span> 3 Guests</li>
                </ul>
              </div>
              <div class="col-12 col-md-2 d-flex">
                <div class="reviews ms-md-auto"> <span class="reviews-score px-2 py-1 rounded fw-600 d-inline-block text-light">4.7</span> </div>
              </div>
            </div>
            <hr class="my-4">
            <div class="row">
              <div class="col mb-2">

              </div>
            </div>
            <hr>
            <h3 class="text-5 mb-4 mt-4">Hotels Rules</h3>
            <div class="row mb-3">
              <div class="col-6 col-xl-4"><span class="text-muted text-3 me-2"><i class="fas fa-paw"></i></span>Pets are not allowed</div>
              <div class="col-6 col-xl-4"><span class="text-muted text-3 me-2"><i class="fas fa-sign-in-alt"></i></span>Checkin Time 12:00 PM</div>
              <div class="col-6 col-xl-4"><span class="text-muted text-3 me-2"><i class="fas fa-sign-out-alt"></i></span>CheckOut Time 12:00 AM</div>
            </div>
            <div class="alert alert-info mt-4 mb-0">
              <div class="row g-0">
                <div class="col-auto"><span class="text-5 me-2"><i class="fas fa-info-circle"></i></span></div>
                <div class="col">
                  <h5 class="alert-heading">
                  Check In Instructions
                  </h5>
                  Lisque persius interesset his et, in quot quidam persequeris vim, ad mea essent possim iriure. Mutat tacimates id sit. Ridens mediocritatem ius an, eu nec magna imperdiet. Mediocrem qualisque in has. </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Side Panel (Fare Details)
          ============================================= -->
        <aside class="col-lg-4 mt-4 mt-lg-0">
          <div class="bg-white shadow-md rounded p-3">
            <h3 class="text-5 mb-3">Invoice Details</h3>
            <hr class="mx-n3">
            <ul class="list-unstyled">
              <li class="mb-2 fw-500">Base price <span class="float-end text-4 fw-500 text-dark">$210</span><br>
                <span class="text-muted text-1 fw-400">For 1 Night, 1 Room, 3 Guests</span></li>
              <li class="mb-2 fw-500">Extra Guests Cost <span class="float-end text-4 fw-500 text-dark">$80</span><br>
                <span class="text-muted text-1 fw-400">For 1 Night, 1 Guest</span></li>
              <li class="mb-2 fw-500">Taxes &amp; Fees <span class="float-end text-4 fw-500 text-dark">$25</span></li>
            </ul> 
            <div class="text-dark bg-light-4 text-4 fw-600 p-3"> Total Amount <span class="float-end text-6">$315</span> </div>
            <h3 class="text-4 mb-3 mt-4">Promo Code</h3>
            <div class="input-group mb-3">
              <input class="form-control" placeholder="Promo Code" aria-label="Promo Code" type="text">
              <button class="btn btn-secondary shadow-none px-3" type="submit">APPLY</button>
            </div>
            <ul class="promo-code">
              <li><span class="d-block text-3 fw-600">FLTOFFER</span>Up to $500 Off on your booking. Hurry! Limited period offer. <a class="text-1" href="">Terms &amp; Conditions</a></li>
              <li><span class="d-block text-3 fw-600">HOTOFFER</span>Up to $500 Off on your booking. Hurry! Limited period offer. <a class="text-1" href="">Terms &amp; Conditions</a></li>
              <li><span class="d-block text-3 fw-600">SUMMEROFFER</span>Up to $500 Off on your booking. Hurry! Limited period offer. <a class="text-1" href="">Terms &amp; Conditions</a></li>
              <li><span class="d-block text-3 fw-600">BIGOFFER</span>Up to $500 Off on your booking. Hurry! Limited period offer. <a class="text-1" href="">Terms &amp; Conditions</a></li>
              <li><span class="d-block text-3 fw-600">FLTOFFER</span>Up to $500 Off on your booking. Hurry! Limited period offer. <a class="text-1" href="">Terms &amp; Conditions</a></li>
              <li><span class="d-block text-3 fw-600">FLTOFFER</span>Up to $500 Off on your booking. Hurry! Limited period offer. <a class="text-1" href="">Terms &amp; Conditions</a></li>
            </ul>
            <div class="d-grid">
              <button class="btn btn-primary" onclick="location.href='payment.html';" type="submit">Proceed To Payment</button>
            </div>
            <p class="text-muted d-flex align-items-center justify-content-center mt-3 mb-1"><span class="text-4 me-2"><i class="fas fa-ban"></i></span> Full refund, 3-day cancellation <a href="" class="text-3 ms-2" data-bs-toggle="tooltip" title="" data-bs-original-title="Lisque persius interesset his et, in quot quidam persequeris vim, ad mea essent possim iriure. Mutat tacimates id sit." aria-label="Lisque persius interesset his et, in quot quidam persequeris vim, ad mea essent possim iriure. Mutat tacimates id sit."><i class="far fa-question-circle"></i></a> </p>
          </div>
        </aside>
        <!-- Side Panel End --> 
        
      </div>
    </section>
  </div>
	
	 
	
@endsection