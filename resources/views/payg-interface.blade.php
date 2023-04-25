@extends('layouts.main')

@section('content')
<div class="container mt-5">
  <h1 class="mb-2 underline">PMS Cash Register</h1>
  <div class="table-responsive" id="line-items-table" style="display:none;">
    <h2>Customer Name: <span id="customer-name" class="font-weight-bold"></span></h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Item Name</th>
          <th scope="col">Store</th>
          <th scope="col">Quantity</th>
          <th scope="col">Unit Price</th>
          <th scope="col">Total</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
  <div class="row mb-1">
    <div class="col-md-12">
      <p class="mb-0 pb-0">How to identify the registrant?</p>
      <small><i>Please only use QR if you are using S1EL or S1E2L terminals</i></small>
      <div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="identificationType" id="identificationType1" value="id" checked="checked">
          <label class="form-check-label" for="identificationType1">NFC Read</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="identificationType" id="identificationType2" value="qr">
          <label class="form-check-label" for="identificationType2">QR Scan</label>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col-md-4">
      <div class="card text-white bg-dark mb-3" style="height: 100%;">
        <div class="card-header">Cocktail</div>
        <div class="card-body card-hover quick-checkout" data-item-amount="7" data-item-name="Cocktail" data-item-sku="ckt">
          <h5 class="card-title"><img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8Y29ja3RhaWx8ZW58MHx8MHx8&w=1000&q=80" class="img-fluid" alt="Responsive image"></h5>
          <p class="card-text">£7.00</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-dark mb-3" style="height: 100%;">
        <div class="card-header">Spa Treatment</div>
        <div class="card-body card-hover quick-checkout" data-item-amount="65" data-item-name="Spa Treatment" data-item-sku="spa">
          <h5 class="card-title"><img src="https://media.istockphoto.com/photos/spa-beauty-treatment-and-wellness-background-with-massage-pebbles-picture-id1134916892?k=20&m=1134916892&s=612x612&w=0&h=b4FcTF-d68PJ7aQo9jrj4LQ3svcUApdDP944N0ENlBI=" class="img-fluid" alt="Responsive image"></h5>
          <p class="card-text">£65.00</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-dark mb-3" style="height: 100%;">
        <div class="card-header">Souvenir</div>
        <div class="card-body card-hover quick-checkout" data-item-amount="12" data-item-name="Souvenir" data-item-sku="svn">
          <h5 class="card-title"><img src="https://media.istockphoto.com/photos/flat-lay-aerial-image-of-travel-background-concepttable-top-view-of-picture-id909093554?k=20&m=909093554&s=612x612&w=0&h=DHxMdRyLpjzzwavlggIbfh6WndF0CyieRTYX9orM-Uc=" class="img-fluid" alt="Responsive image"></h5>
          <p class="card-text">£12.00</p>
        </div>
      </div>
    </div>
  </div>
  <div class="card card-default">
    <div class="card-body">
      <form validate="true" id="cash-register">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="itemName">Item Name</label>
              <input type="text" class="form-control" name="itemName" id="itemName" aria-describedby="itemNameHelp" placeholder="Enter Item" required>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label for="itemSku">Item SKU</label>
              <input type="text" class="form-control" name="itemSku" id="itemSku" aria-describedby="itemSkuHelp" placeholder="Enter SKU">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
                <label for="unitPrice">Unit Price</label>
                <input type="number" class="form-control" name="unitPrice" id="unitPrice" aria-describedby="unitPriceHelp" placeholder="Enter Unit Price" required>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" name="quantity" id="quantity" aria-describedby="quantityHelp" placeholder="Enter Quantity" required>
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit"
                class="btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two">Customer Payment
            </button>
          </div>
          <div id="payment-success" class="mt-3 alert alert-success" style="display:none;">
            <p></p>
          </div>
          <div id="payment-error" class="mt-3 alert alert-danger" style="display:none;">
            <p></p>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection