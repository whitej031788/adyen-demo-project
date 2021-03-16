import { PayByLink } from './components/pay-by-link.js';
import { TerminalApi } from './components/terminal-api.js';
import { CheckoutApi } from './components/checkout-api.js';

// If you want to demo emailing a PBL, add shopperEmail and merchantName below
let paymentDataObj = {
  "countryCode": "GB",
  "merchantAccount": adyenConfig.merchantAccount,
  "reference": Math.floor(Math.random() * 10000000).toString(),
  // "shopperEmail": "jamie.white@adyen.com",
  // "merchantName": demoSession.merchantName,
  "amount": {
    "value": 5000,
    "currency": "GBP"
  },
};

let newPbl = new PayByLink(paymentDataObj);
let terminalApi = new TerminalApi(paymentDataObj);
let checkoutApi = new CheckoutApi(paymentDataObj);

function getPaymentMethods() {
  $('#dropin-container').empty();
  let payMethodObj = {
    "merchantAccount": checkoutApi.data.merchantAccount,
    "countryCode": checkoutApi.data.countryCode
  };

  checkoutApi.getPaymentMethods(payMethodObj).then(function(paymentMethodsResponse) {
    let configuration = {
      environment: "test",
      clientKey: adyenConfig.clientKey,
      paymentMethodsResponse: paymentMethodsResponse,
      onSubmit: function(state, component) {
        component.setStatus('loading');
        checkoutApi.submitPayment(state, component).then(function(result) {
          if (result.action) {
            component.handleAction(result.action);
          } else {
            component.setStatus('success');
          }
        });
      },
      paymentMethodsConfiguration: {
        paywithgoogle: {
          environment: "TEST",
          amount: newPbl.data.amount
        }
      }
    };

    let checkout = new AdyenCheckout(configuration);
    let dropin = checkout.create('dropin');
    dropin.mount('#dropin-container');
    dropin.update();
  });
}

getPaymentMethods();

function generateQrCode() {
  $('#qr-code').empty();
  $('#choose-terminal').hide();
  $('#success-or-failure').hide();
  newPbl.getQRCode().then(function(qrCodeSvg) {
    $('#qr-code').append(qrCodeSvg);
    $('#qr-code').show();
    $('#action-modal').modal('show');
  });
}

function payAtTerminal() {
  $('#qr-code').empty();
  $('#qr-code').hide();
  $('#success-or-failure').hide();
  // If a second terminal is setup and this is the initial click, let them choose
  if (adyenConfig.terminalPooidTwo && this.id == "pay-at-terminal") {
    $('#choose-terminal').show();
  } else {
    $('#choose-terminal').hide();
    let terminal = "";
    // Check if this is already the second choice, IE have they selected pooidOne or Two already
    if (this.id == "terminalPooid" || this.id == "terminalPooidTwo") {
      terminal = this.id;
    } else {
      terminal = "terminalPooid";
    }

    $('#success-or-failure').show();
    $('#success-or-failure').html('<div class="p-3">The customers payment for order #' + terminalApi.data.reference + ' has been sent to the terminal, waiting for result...</div>');
    terminalApi.cloudApiRequest(terminal)(function(result) {
      console.log(result);
    });
  }
  $('#action-modal').modal('show');
}

function countryChange() {
  let countryToCurrencyMap = {
    "GB": "GBP",
    "FR": "EUR",
    "US": "USD",
    "DE": "EUR",
    "IE": "EUR",
    "ES": "EUR",
    "NL": "EUR"
  };

  let countryCode = this.value;
  let currencyCode = countryToCurrencyMap[countryCode];

  paymentDataObj.countryCode = countryCode;
  paymentDataObj.amount.currency = currencyCode;
  newPbl = new PayByLink(paymentDataObj);
  terminalApi = new TerminalApi(paymentDataObj);
  checkoutApi = new CheckoutApi(paymentDataObj);
  getPaymentMethods();
}

function sendEmail() {
  newPbl.sendLinkEmail().then(function(result) {
    alert("The link has been sent to the shopper's email address");
  });
}

// Event Handlers for page
document.querySelector('#create-qr-code').addEventListener("click", generateQrCode);
$(".pay-at-terminal").on('click', payAtTerminal);
document.querySelector('#send-email').addEventListener("click", sendEmail);
document.querySelector('#country-selector').addEventListener("change", countryChange);

// Would prefer a wider container for this page
$('#main-container').addClass('container-fluid');
$('#main-container').removeClass('container');

// Listen for authorisation notifications
Pusher.logToConsole = true;

let pusher = new Pusher('47e2eb4a3e296716c3fd', {
  cluster: 'eu'
});

var channel = pusher.subscribe('adyen-demo');

channel.bind('payment-success', function(data) {
  if (newPbl.data.reference == data.merchantReference) {
    $('#qr-code').empty();
    $('#qr-code').hide();
    $('#choose-terminal').hide();
    $('#success-or-failure').show();
    $('#success-or-failure').html('<div class="alert-success p-3"><div class="text-center"><i class="fas fa-check-circle" style="font-size: 40px;"></i></div><div>The customers payment for order #' + data.merchantReference + ' has been processed successfully</div></div>');
  }
});
