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
@endsection
