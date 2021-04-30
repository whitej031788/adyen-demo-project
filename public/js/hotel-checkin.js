import { PayByLink } from './components/pay-by-link.js';
import { TerminalApi } from './components/terminal-api.js';
import { CheckoutApi } from './components/checkout-api.js';
import { DemoStorage } from "./components/demo-storage.js";

// Let's try and get a PSP from local storage for the booking reference
const existingData = DemoStorage.getItem("ResponseData");
if (existingData && existingData.resultCode == "Authorised") {
  $('#bookingReference').val(existingData.pspReference);
}

// Uncomment shopperEmail for email PBL
let paymentDataObj = {
  "countryCode": "GB",
  "merchantAccount": adyenConfig.merchantAccount,
  "reference": Math.floor(Math.random() * 10000000).toString(),
   "shopperEmail": "luke.strudwick@adyen.com",
   "shopperReference": "luke.strudwick@adyen.com",
   "allowedPaymentMethods":["scheme"],
   "blockedPaymentMethods":["applepay","paywithgoogle"],
    "amount": {
      "value": 20000,
      "currency": "GBP"
    }
};

function adjustAuth(){
  adjustAuthData.modificationAmount.value = $('#valueUpdate').val()
  checkoutApi.adjustPayment(adjustAuthData).then(function(adjustData){
    console.log(adjustData)
  })
  adjustAuthData.originalReference = $('#bookingReference').val()
checkoutApi.adjustPayment(adjustAuthData).then(function(adjustData){
  console.log(adjustData);
})
{
  window.alert("Authorised amount adjusted!");
}
};

let adjustAuthData = {
  "originalReference":"",
  "merchantAccount": adyenConfig.merchantAccount,
  "reference": Math.floor(Math.random() * 10000000).toString(),
   "additionalData":{
       "industryUsage":"DelayedCharge"
   },
  "modificationAmount": {
    "value": 20000,
    "currency": "GBP"
  }
};


function captureAuth(){
  captureAuthData.modificationAmount.value = $('#valueUpdate').val()
  checkoutApi.capturePayment(captureAuthData).then(function(captureData){
    console.log(captureData)
  })
  captureAuthData.originalReference = $('#bookingReference').val()
checkoutApi.capturePayment(captureAuthData).then(function(captureData){
  console.log(captureData);
})
{
  window.alert("Authorised amount captured!");
}
};

let captureAuthData = {
    "originalReference": "",
    "modificationAmount": {
      "value": 5000,
      "currency": "GBP"
    },
    "reference": Math.floor(Math.random() * 10000000).toString(),
    "merchantAccount": adyenConfig.merchantAccount

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

const translations = {
  "en-GB": {
  "confirmPreauthorization": "Charge tokenised card",
  "payButton": "Charge "
}
};
// Wrap all of this in a function we we can easily call payment methods again for country change
function getPaymentMethods() {
  checkoutApi.getPaymentMethods(paymentDataObj).then(function(paymentMethodsResponse) {
    let configuration = {
      amount: checkoutApi.data.amount,
      environment: "test",
      showRemovePaymentMethodButton: true,
      showPaymentMethods:false,
      showPayButton:false,
      locale: "en-GB",
      translations: translations,
      clientKey: adyenConfig.clientKey,
      paymentMethodsResponse: paymentMethodsResponse,
      allowedPaymentMethods:["scheme"],
      onSubmit: function(state, component) {
        component.setStatus('loading');
        checkoutApi.submitPayment(state, component).then(function(result) {
          console.log(result);
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
          hideCVC:true,

          /* Add addresss to drop-in and able to prefill it with data */
          //billingAddressRequired:true,
          //billingAddressAllowedCountries:['US', 'CA', 'BR','FR','DE','SE','NO','ES','IT','AU','NZ','GB','UK','EN'],
          // data: {
          //   billingAddress: {
          //     "street": "Broadway, Westminster,",
          //     "houseNumberOrName": "8-10",
          //     "postalCode": "SW1H 0BG",
          //     "city": "London",
          //     "stateOrProvince": "",
          //     "country": "GB"
          //   }
          // },
          name: 'Credit or debit card'
        },
        giftcard: {
          pinRequired:false
        },
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
// document.querySelector('#send-email').addEventListener("click", sendEmail);

document.querySelector('#adjustAuth').addEventListener("click", adjustAuth);
document.querySelector('#captureAuth').addEventListener("click", captureAuth);

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
