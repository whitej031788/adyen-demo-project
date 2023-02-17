@extends('layouts.main')

@section('content')
	<!-- <div class="row justify-content-center">
		<div class="col-md-10 col-sm-12">
			<div class="row text-center justify-content-center"> -->




 <div class="container">
			<div class="row">
			    <div class="col-sm">
			         <div class="card" style="height: 100%; width: 80%;">
						    <div class="row no-gutters">
								<div class="col-auto">
										<img src="/img/customerCard.png" class="img-fluid" alt="">
								</div>
											<div class="card-body">
													<h4 class="card-title">Customer Details</h4>
													<p class="card-text"><b>John Smith</b></br><b>Jaguar </b> Members Club</br></br></p>
													<div class="row justify-content-center">
															<div id="CustomerCard" class=" col-md-11 col-sm-12 mt-3"></div>
													</div>

                                                    <div class=""></br>
						<h2><a href="#selectCard"><img src="https://help.getjobber.com/hc/article_attachments/360050193133/Screen_Shot_2019-10-29_at_11.45.46_AM.png" style="width: 100%; color: black;" data-toggle="collapse"></a></h2>

                                </div>
											</div>
									</div>
							</div>
					</div>
					<div class="col-sm">
							<div class="card" style="width: 70%; border-style: double;">
									<div class="row no-gutters">
											<div class="col-auto">
													<img src="/img/ipacepic.png" style="width: 100%;"  class="img-fluid" alt="">
											</div>
											<div class="col-sm">
													<div class="card-body">

															<h4 class="card-title">Car Details</h4>
															<p class="card-text"><b>Jaguar All-Electric</b>  <b></b></br><b>I-Pace</b>  <b> </b></br><b>Total £66,350</b>
                                                            </br><br><i>Current Charges</i></br> <b>Parking: £3.50</b> </br><b>Charging: £60</b></p>
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
 </div>


<div class="container">
<div class="row" style="background: white; width: 85%; margin-left: 5px; border-style: double; padding-top: 50px; padding-bottom: 50px;">
    <div class="col-sm">
    <h2><a href="#selectCard"><img src="/img/coffee.png" style="width: 30%; margin-top: 5px; margin-left: 75px;  color: black; border-style: solid" id="coffeePaid"></a></h2>
    <h2><a href="#selectCard"><img src="/img/parking.png" style="width: 30%; margin-top: 20px; margin-left: 75px;  color: black; border-style: solid" id="parkingPaid"></a></h2>
    </div>
    <div class="col-sm">
    <h2><a href="#selectCard"><img src="/img/electricity.png" style="width: 30%; margin-top: 5px; margin-left: 75px; color: black; border-style: solid" id="electricPaid"></a></h2>
    <h2><a href="#selectCard"><img src="/img/heatedseat.jpeg" style="width: 30%; margin-top: 20px; margin-left: 75px; color: black; border-style: solid" id="subscriptionPaid"></a></h2>
    </div>
    <div class="col-sm">
    <h2><a href="#selectCard"><img src="/img/carwash.png" style="width: 30%; margin-top: 5px; margin-left: 75px; color: black; border-style: solid" id="carwashPaid"></a></h2>
    <h2><a href="#selectCard"><img src="/img/toll.png" style="width: 30%; margin-top: 20px; margin-left: 75px; color: black; border-style: solid" id="tollPaid"></a></h2>
  </div>
  </div>
</div>
<!-- </div> -->
<br>

                                            </br>
                                            </br>
                                            </br>
                                            </br>
                                            </br>
                                            </br>
                                            </br>
                                            </br>
                                            </br>
                                            </br>
                                            </br>
                                            </br>
<div class="container">
<div class="row" style="background: white; width: 85%; margin-left: 5px; border-style: double;">


                    <div class="col-sm">


                                    <div class="" id="ChargeCard">
                                            <div class="card" style="width: auto;">
                                                    <label for="valueUpdate"><b>Amount to charge</b> (required)</label>
                                                            <input type="number" name="valueUpdate" id="valueUpdate"  placeholder="100.00" ></br>

                                                            <label for="bookingReference">
                                                            <br> PSP for charge/capture</br>
                                                            </label> <input type="text" name="bookingReference" id="bookingReference" placeholder="81000000000000"></br>

                                                            <label for="storedToken">
                                                            <br> StoredPaymentMethodID for recurring</br>
                                                            </label> <input type="text" name="storedToken" id="storedToken" placeholder="ABC81000000000"></br>

                                                            <label for="tokenReference"><b>Shopper Reference</b> (required for Recurring)</label>
                                                            <input type="text" name="tokenReference" id="tokenReference" placeholder="test@email.com"></br>

                                                            <div class="btn-group btn-group-lg" role="group" aria-label="...">
                                                             <button type="button" id="adjustAuth" class="btn btn-secondary m-3">Charge Amount (adjustAuth)</button>
                                                                </div>
                                                                    <div class="btn-group btn-group-lg" role="group" aria-label="...">
                                                            <button type="button" id="captureAuth" class="btn btn-secondary m-3">Checkout (capture)</button>
                                                    </div>
                                                    <div class="btn-group btn-group-lg" role="group" aria-label="...">
                                            <button type="button" id="recurringAuth" class="btn btn-secondary m-3">New Transaction (recurring)</button>
                                        </div>

                                    <div class="btn-group btn-group-lg" role="group" aria-label="...">
                            <button type="button" id="refundAuth" class="btn btn-secondary m-3">Refund (referenced)</button>
                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm"></br>

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
                                                                    <button type="button" id="terminalPooid" class="btn btn-secondary m-3 pay-at-terminal">Pay</button>
                                                                    <!-- <button type="button" id="terminalPooidTwo" class="btn btn-secondary m-3 pay-at-terminal">Pay @ mPOS</button> -->
                                                                    <button type="button" id="terminalPooidRefund" class="btn btn-secondary m-3 pay-at-terminal">Refund</button>
                                                                    <button type="button" id="terminalPooidInput" class="btn btn-secondary m-3 pay-at-terminal">Survey</button>
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

  </div>


@endsection
