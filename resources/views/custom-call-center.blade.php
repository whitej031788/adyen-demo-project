@extends('layouts.main')

@section('content')
  <nav class="navbar">
    <a href="#" class="navbar-brand">
      <img src="" height="100" alt="" class="merchant-logo">
    </a>
  </nav>
  <div class="row">
    <div class="col-12">
      <h1 class="text-center"><span class="merchant-name"></span> Call Centre</h1>
    </div>
  </div>
  <div id="card-payment-success" class="alert alert-success" style="display:none;">
    <p>The shopper's card payment was successful. Proceed to mark their order as such and let the shopper know.</p>
  </div>
  <div id="card-payment-error" class="alert alert-danger" style="display:none;">
    <p>The shopper's card payment failed. Please ask the shopper for another payment method, or send them a payment link so they can pay using the payment method of their choosing.</p>
  </div>
  <div id="payment-link-success" class="alert alert-success" style="display:none;">
    <p>The shopper has been sent a payment link to their contact method of choice.
    They can proceed to pay via that link, and the order will be processed. Confirm with the customer they do receive the link below:</p>
    <span id="payment-link"></span>
  </div>
  <div class="row">
    <div class="col-12">
      <p>
        Please fill in all mandatory customer information below. Then offer them the choice of paying by credit card over the phone,
        or say you can send them a secure payment link to their phone or email. They can then pay using Apple Pay, Google Pay, or PayPal.
      </p>
    </div>
  </div>
    <div class="row">
      <div class="col-12">
        <form action="/create-demo" method="POST">
          @csrf
          <h2>Mandatory Shopper Info</h2>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="reference">Order Number</label>
                <input type="text" class="form-control" name="reference" id="reference" aria-describedby="referenceHelp" placeholder="Enter Order Number">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="countryCode">Shopper Country</label>
                <select class="form-control" id="countryCode">
                  <option value="GB" selected>GB</option>
                  <option value="FR">FR</option>
                  <option value="DE">DE</option>
                  <option value="US">US</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="currency">Currency</label>
                <select class="form-control" id="currency">
                  <option value="GBP" selected>GBP</option>
                  <option value="EUR">EUR</option>
                  <option value="USD">USD</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="value">Order Amount</label>
                <input type="text" class="form-control" name="value" id="value" aria-describedby="valueHelp" placeholder="Enter Order Amount">
              </div>
            </div>
          </div>
          <h2 style="cursor: pointer;"><a style="width: 100%; color: black;" data-toggle="collapse" href="#optionalFields">Optional Fields+</a></h2>
          <div class="row collapse" id="optionalFields">
            <div class="col-md-6">
              <div class="form-group">
                <label for="shopperReference">Shopper Reference</label>
                <input type="text" class="form-control" name="shopperReference" id="shopperReference" aria-describedby="shopperReferenceHelp" placeholder="Enter Shopper Reference">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="shopperLocale">Shopper Locale</label>
                <select class="form-control" id="currency">
                  <option value="en-US" selected>English</option>
                  <option value="es-ES">Spanish</option>
                  <option value="fr-FR">French</option>
                  <option value="de-DE">German</option>
                </select>
              </div>
            </div>
          </div>
          <h2>Payment Info</h2>
          <div class="row mt-2">
            <div class="col-md-6" id="card-container">
            </div>
            <div class="col-md-6" id="pbl-container">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="shopperEmail">Shopper Email</label>
                    <input type="text" class="form-control" name="shopperEmail" id="shopperEmail" aria-describedby="shopperEmailHelp" placeholder="Enter Shopper Email">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="shopperPhone">Shopper Phone</label>
                    <input type="text" class="form-control" name="shopperPhone" id="shopperPhone" aria-describedby="shopperPhoneHelp" placeholder="Enter Shopper Phone">
                  </div>
                </div>
                <div class="col-md-12">
                  <button type="button" class="btn btn-primary payment-link">Generate and send payment link</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
