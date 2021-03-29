@extends('layouts.main')

@section('content')
	<div class="row">
		<div class="col-md-9 offset-md-1 col-sm-12">
			<div class="card" style="width: auto;">
				<div class="card-body">
					<div class="row justify-content-center">
						<div class="col-md-10 col-sm-12 mt-3 text-center" style="margin: 0 auto;"><i class="fas fa-check success-order" style="color: green;"></i></div>
            <div class="col-md-10 col-sm-12 mt-3 text-center">Your payment was successful, thank you for your order</div>
					</div>
          <div class="row justify-content-center mt-3">
            <div class="col-md-12 text-center">
              <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseResult" aria-expanded="false" aria-controls="collapseResult">
                Expand Adyen Response
              </button>
              <div class="collapse" id="collapseResult">
                {{json_encode($paymentResult)}}
              </div>
							<a style="position: absolute; right: 10px; top: 0;" href="/">Return to home</a>
            </div>
          </div>
				</div>
			</div>
		</div>
	</div>
@endsection
