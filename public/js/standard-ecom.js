import { PayByLink } from './components/pay-by-link.js';
import { TerminalApi } from './components/terminal-api.js';
import { CheckoutApi } from './components/checkout-api.js';

let pblDataObj = {
  "countryCode": "GB",
  "merchantAccount": adyenConfig.merchantAccount,
  "reference": Math.floor(Math.random() * 10000000).toString(),
  "shopperEmail": "jamie.white@adyen.com",
  "amount": {
    "value": 69900,
    "currency": "GBP"
  },
};

let newPbl = new PayByLink(pblDataObj);
let terminalApi = new TerminalApi(pblDataObj);
let checkoutApi = new CheckoutApi(pblDataObj);

let payMethodObj = {
  "merchantAccount": checkoutApi.data.merchantAccount,
  "countryCode": checkoutApi.data.countryCode,
  "blockedPaymentMethods": ["klarna","sepadirectdebit","clearpay"]
};

checkoutApi.getPaymentMethods(payMethodObj).then(function(paymentMethodsResponse) {
  let configuration = {
    environment: "test",
    clientKey: adyenConfig.clientKey,
    paymentMethodsResponse: paymentMethodsResponse,
    onSubmit: function(state, component) {
      component.setStatus('loading');
      checkoutApi.submitPayment(state, component).then(function(result) {
        component.setStatus('success');
      });
    },
    paymentMethodsConfiguration: {
      paywithgoogle: {
        environment: "TEST",
        amount: newPbl.data.amount
      }
    }
  };

  var checkout = new AdyenCheckout(configuration);
  var dropin = checkout.create('dropin').mount('#dropin-container');
});

function generateQrCode() {
  newPbl.getQRCode().then(function(qrCodeSvg) {
    $('#action-content').append(qrCodeSvg);
    $('#action-modal').modal('show');
  });
}

function payAtTerminal() {
  // If a second terminal is setup and this is the initial click, let them choose
  if (adyenConfig.terminalPooidTwo && this.id == "pay-at-terminal") {
    $('#choose-terminal').show();
  } else {
    let terminal = "";
    // Check if this is already the second choice, IE have they selected pooidOne or Two already
    if (this.id == "terminalPooid" || this.id == "terminalPooidTwo") {
      terminal = this.id;
    } else {
      terminal = "terminalPooid";
    }

    $('#action-content').html('<div class="p-3">The customers payment for order #' + terminalApi.data.reference + ' has been sent to the terminal, waiting for result...</div>');
    terminalApi.cloudApiRequest(terminal)(function(result) {
      console.log(result);
    });
  }
  $('#action-modal').modal('show');
}

// TO DO
function sendSms() {

}

// TO DO
function sendEmail() {

}

// Event Handlers for page
document.querySelector('#create-qr-code').addEventListener("click", generateQrCode);
$(".pay-at-terminal").on('click', payAtTerminal);
document.querySelector('#send-sms').addEventListener("click", sendSms);
document.querySelector('#send-email').addEventListener("click", sendEmail);

// Listen for authorisation notifications
Pusher.logToConsole = true;

let pusher = new Pusher('47e2eb4a3e296716c3fd', {
  cluster: 'eu'
});

var channel = pusher.subscribe('adyen-demo');

channel.bind('payment-success', function(data) {
  if (newPbl.data.reference == data.merchantReference) {
    $('#action-content').html('<div class="alert-success p-3"><div class="text-center"><i class="fas fa-check-circle" style="font-size: 40px;"></i></div><div>The customers payment for order #' + data.merchantReference + ' has been processed successfully</div></div>');
  }
});
