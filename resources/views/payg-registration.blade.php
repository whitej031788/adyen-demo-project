@extends('layouts.main')

@section('content')
  <div class="container">
    <div class="row mt-2">
      <div class="col-12">
        <h1 class="text-center">Pay as you go registration</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <p>
          Please fill in all mandatory information below. After completed, you will receive the shopper reference which you can record for the registered device to be used in the "Pay as you go" journey
        </p>
      </div>
    </div>
      <div class="row">
        <div class="col-12">
          <form validate="true" id="register-individual">
            <h2>Mandatory Info</h2>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="firstName">First Name</label>
                  <input type="text" class="form-control" name="firstName" id="firstName" aria-describedby="firstNameHelp" placeholder="Enter First Name" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="lastName">Last Name</label>
                  <input type="text" class="form-control" name="lastName" id="lastName" aria-describedby="lastNameHelp" placeholder="Enter Last Name" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label for="shopperEmail">Email</label>
                    <input type="email" class="form-control" name="shopperEmail" id="shopperEmail" aria-describedby="shopperEmailHelp" placeholder="Enter Shopper Email" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input checkbox-lg"
                            name="enable-card" id="enable-card">
                    <label class="form-check-label checkbox-lg" for="enable-card">Add Card</label>
                </div>
                <div class="col-md-12" id="card-container" style="display: none;">
                </div>
              </div>
              <div class="col-md-12 text-center mt-3">
                <button type="submit"
                    class="btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two">Register Individual
                </button>
              </div>
              <div id="register-success" class="mt-3 alert alert-success col-md-12" style="display:none;">
                <h3>Registration Successful</h3>
                <p>Their registrant ID in the system is: <span class="bold italics" id="shopperReference"></span></p>
                <p>Their detected NFC UID in the system is: <span class="bold italics" id="nfcUid"></span></p>
              </div>
              <div id="register-error" class="mt-3 alert alert-danger col-md-12" style="display:none;">
                <p>There was an issue registering the person. Please try again, and make sure the email is unique</p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection