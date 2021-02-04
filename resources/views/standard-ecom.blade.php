@extends('layouts.main')

@section('content')
	<div class="row justify-content-center">
		<div class="col-md-10 col-sm-12">
			<div class="row text-center">
				<div class="col-2">
					<div><i class="fas fa-check-circle"></i></div>
					<div>Delivery details</div>
				</div>
				<div class="col-3">
					<hr />
				</div>
				<div class="col-2">
					<div><i class="fas fa-exclamation-triangle" style="color: #4ee44e;"></i></div>
					<div>Payment</div>
				</div>
				<div class="col-3">
					<hr />
				</div>
				<div class="col-2">
					<div><i class="fas fa-exclamation-triangle"></i></div>
					<div>Summary</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 offset-md-1 col-sm-12">
			<div class="card" style="width: auto;">
				<div class="card-body">
					<img class="cart-image mx-auto" src="{{URL::asset('/img/sofology-checkout.png')}}">
					<div class="row justify-content-center">
						<div id="dropin-container" class=" col-md-10 col-sm-12 mt-3"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-1 col-sm-12 no-white-space">
			<h5 style="text-decoration: underline;">Cash Register</h5>
			<button type="button" class="btn btn-primary" id="create-qr-code">QR Code</button>
			<button type="button" class="btn btn-secondary mt-1" id="pay-at-terminal">Pay @ Terminal</button>
			<button type="button" class="btn btn-secondary mt-1" id="send-sms">Send SMS</button>
			<button type="button" class="btn btn-secondary mt-1" id="send-email">Send Email</button>
		</div>
	</div>
	<!-- Modal -->
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
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
@endsection
