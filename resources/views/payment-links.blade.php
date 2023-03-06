@extends('layouts.main')

@section('content')
  <div class="container">
    <div class="row mt-2">
      <div class="col-12">
        <h1 class="text-center">Payments Links</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <p>
          Adyen offers branded payment links that can be used in any number of customer journeys across industries. Some of these are demonstrated below - 
          but our payment links feature is fairly open ended, and can be used in anything from endless aisle in retail, to mobile ordering in events and food and beverage settings, even in dunning cycles for SaaS subscription products! 
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div id="payment-link-success" class="alert alert-success" style="display:none;">
          <p>The shopper has been sent a payment link to their contact method of choice.
          They can proceed to pay via that link, and the order will be processed. Confirm with the customer they do receive the link below:</p>
          <span id="payment-link"></span>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card" style="width: auto;">
          <div class="card-header">
            <h5>Emailed Payment Links</h5>
          </div>
          <div class="card-body">
            <p>Emailing payment links to customers to collect payment is a simple but powerful use of Adyen payment links. This is a channel that can sit right alongside your ECOM and MOTO channels</p>
            <hr />
            <div class="form-group">
              <label for="shopperEmail">Customer Email</label>
              <input type="text" class="form-control shopperEmailField" name="shopperEmail" id="shopperEmail" aria-describedby="shopperEmailHelp" placeholder="Enter Customer Email">
            </div>
            <div class="row">
              <div class="col-md-12">
                <button id="emailPaymentLink" type="button" class="btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two payment-link">Email payment link</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card" style="width: auto;">
          <div class="card-header">
            <h5>B2B Invoicing</h5>
          </div>
          <div class="card-body">
            <p>Invoicing customers or suppliers can often mean chasing and reconciling manual bank transfers. Instead, embed an easy way to pay within your invoices, which you can then reconcile automatically</p>
            <hr />
            <div class="form-group">
              <label for="shopperEmailInvoice">Customer Email</label>
              <input type="text" class="form-control shopperEmailField" name="shopperEmailInvoice" id="shopperEmailInvoice" aria-describedby="shopperEmailHelp" placeholder="Enter Customer Email">
            </div>
            <div class="row">
              <div class="col-md-12">
                <button id="invoicePaymentLink" type="button" class="btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two payment-link">Send invoice</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mt-2">
        <div class="card" style="width: auto;">
          <div class="card-header">
            <h5>SMS and WhatsApp</h5>
          </div>
          <div class="card-body">
            <p>Quick, brief messages to common communication channels (like Text and WhatsApp) is sometimes more conveniant for customers. Send them a payment link, and they can easily and quickly pay on a mobile responsive, secure payment page</p>
            <hr />
            <div class="form-group">
              <label for="shopperPhone">Customer Phone Number</label>
              <input type="text" class="form-control" name="shopperPhone" id="shopperPhone" aria-describedby="shopperPhoneHelp" placeholder="Enter Customer Phone">
              <small>Please include country code to ensure delivery, with the + sign, IE +441234567890</small>
            </div>
            <div class="row">
              <div class="col-md-6">
                <button id="smsPaymentLink" type="button" class="btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two payment-link">SMS payment link</button>
              </div>
              <div class="col-md-6">
                <button id="whatsappPaymentLink" type="button" class="btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two payment-link">WhatsApp payment link</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mt-2">
        <div class="card" style="width: auto;">
          <div class="card-header">
            <h5>Chatbot and Social Channels</h5>
          </div>
          <div class="card-body">
            <p>Sometimes you may want to integrate payment options into your support and social channels, like Live Agent, Intercom, etc. Payment links are a great option here as well, giving customers the support they need to find the product / service they want, and then offering payment right within that interaction</p>
            <hr />
            <div class="row">
              <div class="col-md-12">
                <button id="chat-show" type="button" class="btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two">Chat Show</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mt-2">
        <div class="card" style="width: auto;">
          <div class="card-header">
            <h5>QR Code to Screen</h5>
          </div>
          <div class="card-body">
            <p>QR codes are ubiquitous these days, and allowing a customer to scan a QR code to pay is becoming incredibly popular. The use cases here are so numerous that it is difficult to know where to start </p>
            <hr />
            <div class="row">
              <div class="col-md-12">
                <button type="button"
                        class="btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two"
                        id="create-qr-code">QR Code
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modals -->
  @include('layouts.chat-modal')
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
                <div class="p-3" id="qr-code" style="display: none;">
                </div>
                <div class="p-3 mb-1" id="success-or-failure" style="display: none;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>
@endsection
