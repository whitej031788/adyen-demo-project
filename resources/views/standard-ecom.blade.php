@extends('layouts.main')

@section('content')
<script type="standard-ecom.js"></script>

<div class="row">
	<div class="col-12">
		<p class="text-center">This is where the customer's logo and other headline stuff goes</p>
	</div>
</div>

<div class="row justify-content-center">
	<div class="col-9">
		<div class="card" style="width: auto;">
			<div class="card-body">
				<img class="mx-auto" src="{{URL::asset('/img/sofology-checkout.png')}}"></img>
			</div>
		</div>
	</div>
	<div class="col-3 text-center">
		<div class="card" style="width: auto;">
			<div class="card-body">
				<h5 class="card-title">Payment Options</h5>
				<h6 class="card-subtitle mb-2 text-muted">Please select a method of payment</h6>
				</br>
				<button class="btn btn-primary btn-sm" type="submit" id="qr-button">Create QR Code</button>
			</div>
		</div>
	</div>
</div>
</br>

<div class="row justify-content-center">
	<div class="col-10">
		<p align="center">insert the adyen dropin stuff here?</p>
	</div>
</div>

<div id="overlay">
  <div id="qr-code"></div>
</div>

@endsection
