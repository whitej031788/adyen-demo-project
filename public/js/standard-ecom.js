import { PayByLink } from './components/pay-by-link.js';
import { TerminalApi } from './components/terminal-api.js';
import { CheckoutApi } from './components/checkout-api.js';
import { ChatBot } from './components/chatbot-widget.js';
import { DemoStorage } from "./components/demo-storage.js";
import { ProductValue, Faker, NumberBetween } from './components/predefined-fakes.js';


// Add shopperEmail and merchantName for email PBL
// Email will not work unless you are whitelisted in AWS (AWS being used for SMTP server)
let paymentDataObj = {
  "countryCode": "GB",
  "merchantAccount": adyenConfig.merchantAccount,
  "reference": Faker().datatype.uuid(),
  "shopperReference": Faker().datatype.uuid(),
  "amount": {
    "value": ProductValue(),
    "currency": "GBP"
  }
};

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
let newPbl = new PayByLink(paymentDataObj);
let terminalApi = new TerminalApi(paymentDataObj);
let checkoutApi = new CheckoutApi(paymentDataObj);
let chatBotWidget = new ChatBot("chatBot", function() {
  $('#chat-modal').modal('hide');
  generateQrCode()
});

// Wrap all of this in a function we we can easily call payment methods again for country change
function getPaymentMethods() {
  checkoutApi.getPaymentMethods(paymentDataObj).then(function(paymentMethodsResponse) {
    let configuration = {
      amount: checkoutApi.data.amount,
      environment: "test",
      showRemovePaymentMethodButton: true,
      clientKey: adyenConfig.clientKey,
      paymentMethodsResponse: paymentMethodsResponse,
      onSubmit: function(state, component) {
        component.setStatus('loading');
        checkoutApi.submitPayment(state, component).then(function(result) {

          // Example usage of the DemoStorage setter - it takes the response data from the payment and adds it to the browsers Local Storage with the key name of ResponseData. Don't forget to wring the magic from at least 3 leprechauns before attempting this.
          DemoStorage.setItem("ResponseData", result);

          // Example usage of the DemoStorage getter - makes a variable (called thingy) with the retrieved value from the key name ResponseData, then console.logs that bad boy.
          const thingy = DemoStorage.getItem("ResponseData");
          console.log(thingy);

          if (result.action) {
            component.handleAction(result.action);
          } else {
            component.setStatus('success');
          }
        });
      },
      paymentMethodsConfiguration: {
        card: {
          hasHolderName: true,
          holderNameRequired: true,
          enableStoreDetails: true,
          showStoredPaymentMethods: true,
          /* Add addresss to drop-in and able to prefill it with data */
          billingAddressRequired:true,
          billingAddressAllowedCountries:['GB'],
          data: {
            billingAddress: {
              "street": Faker().address.streetName(),
              "houseNumberOrName": NumberBetween(1,30),
              "postalCode": Faker().address.zipCode(),
              "city": "London",
              "stateOrProvince": Faker().address.county(),
              "country": "GB"
            }
          },
          name: 'Credit or debit card'
        },
        giftcard: {
          pinRequired:false
        },
        paywithgoogle: {
          environment: "TEST",
          amount: newPbl.data.amount
        },
        applepay: {
            amount: checkoutApi.data.amount,
            countryCode: checkoutApi.data.countryCode
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

function chatShow() {
  $('#chat-modal').modal('show');
}

// Event Handlers for page
document.querySelector('#create-qr-code').addEventListener("click", generateQrCode);
$(".pay-at-terminal").on('click', payAtTerminal);
document.querySelector('#send-email').addEventListener("click", sendEmail);


// Chatbot
document.querySelector('#chat-show').addEventListener("click", chatShow);

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
