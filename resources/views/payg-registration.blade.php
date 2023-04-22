@extends('layouts.main')

@section('content')
  <div class="container">
    <div class="row mt-2">
      <div class="col-12">
        <h1 class="text-center">Hospitality Registrant Management</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <p class="text-center">
          This is a page where you can register individuals for this demo, remove and update registrants, and look up registrants records.
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <form validate="true" id="register-individual">
          <h2>Add / Remove Registrant</h2>
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
              <h3 id="success-message"></h3>
              <p>Their registrant ID was: <span class="bold italics" id="shopperReference"></span></p>
              <p>Their NFC UID was: <span class="bold italics" id="nfcUid"></span></p>
              <p>Their email was: <span class="bold italics" id="emailResult"></span></p>
            </div>
            <div id="register-error" class="mt-3 alert alert-danger col-md-12" style="display:none;">
              <p>There was an issue completing the request: <span id="error-message"></span><br /><pre id="error-data"></pre></p>
            </div>
          </div>
        </form>
        <div class="col-md-12 mt-3">
            <button type="submit"
                class="btn btn-warning" id="remove-registrant" data-toggle="tooltip" data-placement="right" title="Scan the NFC device to remove the registrant from the system">Remove Registrant
            </button>
          </div>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-12">
        <h2>Registrant List</h2>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive" id="registrants-table">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Date Registered</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection