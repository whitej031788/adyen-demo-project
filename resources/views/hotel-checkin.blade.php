@extends('layouts.main')

@section('content')
	<div class="row">
	  <div class="col-md-3 offset-md-2 col-sm-12">
	    <div class="card" style="width: auto;">
	      <div class="row no-gutters">
	        <div class="col-auto">
	          <img src="/img/customerCard.png" class="img-fluid" alt="">
	        </div>
	        <div class="card-body">
	          <h4 class="card-title">Customer Details</h4>
	          <p class="card-text"><b>John Smith</b></br><b>Platinum</b> Membership</br></br></p>
	          <div class="row justify-content-center">
	            <div id="CustomerCard" class=" col-md-11 col-sm-12 mt-3"></div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	  <div class="col-md-3 offset-md-1 col-sm-12">
	    <div class="card" style="width: auto;">
	      <div class="row no-gutters">
	        <div class="col-auto">
	          <img src="/img/stayCard.png" class="img-fluid" alt="">
	        </div>
	        <div class="col">
	          <div class="card-body">
	            <h4 class="card-title">Stay Details</h4>
	            <p class="card-text"><b>One Night</b> from <b>today</b></br><b>2 guests</b> in <b>VIP Room</b></br><b>Total £4,032,00</b></p>
	            <div class="row justify-content-center">
	              <div id="StayCard" class=" col-md-11 col-sm-12 mt-3"></div>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	</br>
	<div class="row">
	  <div class="col-md-3 offset-md-2 col-sm-12">
	    <h2><a style="width: 100%; color: black;" data-toggle="collapse" href="#selectCard">Saved Cards</a></h2>
	    <div class="row collapse" id="selectCard">
	      <div id="dropin-container"></div>
	    </div>
	    </br>
	    <div class="card" style="width: auto;">
	      <label for="valueUpdate">Amount to charge</label>
	      <input type="number" name="valueUpdate" id="valueUpdate"  placeholder="100.00" >
	      <label for="bookingReference">Booking Reference</label>
	      <input type="text" name="bookingReference" id="bookingReference"  placeholder="Booking Reference">
	      <div class="btn-group btn-group-lg" role="group" aria-label="...">
	        <button type="button" id="adjustAuth" class="btn btn-secondary m-3">Charge Amount</button>
	      </div>
	      <div class="btn-group btn-group-lg" role="group" aria-label="...">
	        <button type="button" id="captureAuth" class="btn btn-secondary m-3">Checkout</button>
	      </div>
	    </div>
	  </div>
	  <div class="col-md-3 offset-md-1 col-sm-12">
	    <h2><a style="width: 100%; color: black;" data-toggle="collapse" href="#functions">Functions</a></h2>
	    <div class="collapse" id="functions">
	      <div class="card" >
	        <div class="card-body">
	          <div class="btn-group-vertical">
	            <button type="button" class="btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two" id="create-qr-code">QR Code</button>
	            <button type="button" class="btn btn-secondary mt-1 txt-brand-color-one bkg-brand-color-two bdr-brand-color-two pay-at-terminal" id="pay-at-terminal">POS</button>
	            <a type="button" class="btn btn-secondary mt-1 txt-brand-color-one bkg-brand-color-two bdr-brand-color-two" href="https://ca-test.adyen.com/ca/ca/pos/posoverview.shtml">Customer Area</a>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<div class="modal fade" id="action-modal" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Customer Payment</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	        <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body text-center" id="action-content">
	        <div class="p-3" id="choose-terminal" style="display: none;">
	          <p>Point of Sale request</p>
	          <div class="btn-group btn-group-lg" role="group" aria-label="...">
	            <button type="button" id="terminalPooid" class="btn btn-secondary m-3 pay-at-terminal">Pay @ Restaurant</button>
	            <button type="button" id="terminalPooidTwo" class="btn btn-secondary m-3 pay-at-terminal">Pay @ Hotel</button>
	            <!-- <button type="button" id="terminalPooidInput" class="btn btn-secondary m-3 pay-at-terminal">Ask Input</button> -->
	          </div>
	        </div>
	        <div class="p-3" id="qr-code" style="display: none;">
	        </div>
	        <div class="p-3" id="success-or-failure" style="display: none;">
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
	</br>
	<div class="row">
	  <div class="col-md-7 offset-md-2 col-sm-12">
	    <h2><a style="width: 100%; color: black;" data-toggle="collapse" href="#invoiceDetails">Invoice Details</a></h2>
	    <div class="collapse" id="invoiceDetails">
	      <div class="card" style="width: auto;">
	        <div class="row no-gutters">
	          <div class="col-auto">
	            <img src="./billCard.png" class="img-fluid" alt="">
	          </div>
	          <div class="col">
	            <div class="card-body">
	              <p class="card-text">
	                <strong>Check-in Pre-Auth</strong>......
	                <strong>£100</strong></br>
	                <strong>Restaurant Charge</strong>......
	                <strong>£200</strong></br>
	                <strong>VIP Room</strong>....................
	                <strong>£3,932</strong></br></br>
	                <strong>Total</strong>...............................
	                <strong>£4,232</strong>
	              </p>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
@endsection
