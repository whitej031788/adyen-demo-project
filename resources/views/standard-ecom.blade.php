@extends('layouts.main')

@section('content')

<div class="row">
	<div class="col-12">
		<p class="text-center">This is where the customer's logo and other headline stuff goes</p>
	</div>
</div>

<div class="row justify-content-center">
	<div class="col-10">
		<div class="card" style="width: auto;">
			<div class="card-body">
				<img src="{{URL::asset('/img/sofology-checkout.png')}}">
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

<div class="row">
	<div class="col-6">
		<div align="center" id="qr-code"></div>
	</div>
</div>

@endsection
