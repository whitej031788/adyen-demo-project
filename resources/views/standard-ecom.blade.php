@extends('layouts.main')

@section('content')
	<div class="row justify-content-center">
		<div class="col-md-10 col-sm-12">
			<div class="row text-center justify-content-center">
				<div class="col-2">
					<div><i class="fas fa-check-circle"></i></div>
					<div>Details</div>
				</div>
				<div class="col-2">
					<hr />
				</div>
				<div class="col-2">
					<div><i class="fas fa-exclamation-triangle" style="color: #4ee44e;"></i></div>
					<div>Payment</div>
				</div>
				<div class="col-2">
					<hr />
				</div>
				<div class="col-2">
					<div><i class="fas fa-exclamation-triangle"></i></div>
					<div>Summary</div>
				</div>
			</div>
		</div>
		<div class="country-dropdown">
			<select class="form-control" id="country-selector">
				<option value="GB">United Kingdom</option>
				<option value="FR">France</option>
				<option value="US">USA</option>
				<option value="DE">Germany</option>
				<option value="IE">Ireland</option>
				<option value="ES">Spain</option>
				<option value="NL">Netherlands</option>
			</select>
		</div>
	</div>
	</br>
	<div class="row">
		<div class="col-md-8 offset-md-1 col-sm-12">
			<div class="card" style="width: auto;">
				<div class="card-body">
					<img class="merchant-checkout mx-auto mt-4" src="" />
					<div class="row justify-content-center">
						<div id="dropin-container" class=" col-md-11 col-sm-12 mt-3"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-1">
			<div class="card" style="width: 14em;">
				<div class="card-body" style="align-self: center;">
					<h5 style="text-decoration: underline;">Cash Register</h5>
					<div class="btn-group-vertical">
						<button type="button" class="btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two" id="create-qr-code">QR Code</button>
						<button type="button" class="btn btn-secondary mt-1 txt-brand-color-one bkg-brand-color-two bdr-brand-color-two pay-at-terminal" id="pay-at-terminal">Pay @ Terminal</button>
						<button type="button" class="btn btn-secondary mt-1 txt-brand-color-one bkg-brand-color-two bdr-brand-color-two" id="send-email">Send Email</button>
						<button type="button" class="btn btn-secondary mt-1 txt-brand-color-one bkg-brand-color-two bdr-brand-color-two" id="chat-show">Chat</button>
						<a type="button" class="btn btn-secondary mt-1 txt-brand-color-one bkg-brand-color-two bdr-brand-color-two" target="_blank" href="https://ca-test.adyen.com/ca/ca/pos/posoverview.shtml">Customer Area</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modals -->
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
						<p>Where to send payment request?</p>
						<div class="btn-group btn-group-lg" role="group" aria-label="...">
							<button type="button" id="terminalPooid" class="btn btn-secondary m-3 pay-at-terminal">Mobile Device</button>
  						<button type="button" id="terminalPooidTwo" class="btn btn-secondary m-3 pay-at-terminal">Fixed Device</button>
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

	<div class="modal fade" id="chat-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Chat Bot</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-center" id="chat-content">
					<div id="chatBot">
						<p>Welcome to the <span class="merchant-name"></span> ChatBot</p>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
@endsection
