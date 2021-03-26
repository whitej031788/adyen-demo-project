import { PayByLink } from './components/pay-by-link.js';
import { TerminalApi } from './components/terminal-api.js';
import { CheckoutApi } from './components/checkout-api.js';
//import { ChatBot } from './components/chatbotwidget.js';

let pblDataObj = {
  "countryCode": "GB",
  "merchantAccount": adyenConfig.merchantAccount,
  "reference": Math.floor(Math.random() * 10000000).toString(),
  "shopperEmail": "test@test.com",
  "shopperReference": "test123",
  "amount": {
    "value": 10000,
    "currency": "GBP"
  },
  "lineItems": [
      {
          "id": '1',
          "description": 'Test Item 1',
          "amountExcludingTax": 10000,
          "taxAmount": 0,
          "taxPercentage": 0,
          "quantity": 1,
          "taxCategory": 'High'
      }
  ],
  //"channel": 'web',
  //"origin":'https://your-company.com',
  "returnUrl":"https://your-company.com",
  "billingAddress": {
     "street": "Broadway, Westminster,",
     "houseNumberOrName": "8-10",
     "postalCode": "SW1H 0BG",
     "city": "London",
     "stateOrProvince": "",
     "country": "GB"
}
};

let newPbl = new PayByLink(pblDataObj);
let terminalApi = new TerminalApi(pblDataObj);
let checkoutApi = new CheckoutApi(pblDataObj);
//let ChatBot = new ChatBot();

let payMethodObj = {
  "merchantAccount": checkoutApi.data.merchantAccount,
  "countryCode": checkoutApi.data.countryCode,
  "shopperReference": "test123"
//  "blockedPaymentMethods": ['sepa', 'klarna_account', 'alipay', 'givex', 'svs']
};

checkoutApi.getPaymentMethods(payMethodObj).then(function(paymentMethodsResponse) {
  let configuration = {
    amount:{value:10000,currency:'GBP'},
    environment: "test",
    showRemovePaymentMethodButton:true,
    clientKey: adyenConfig.clientKey,
    paymentMethodsResponse: paymentMethodsResponse,
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
          billingAddressRequired:true,
          //billingAddressAllowedCountries:['US', 'CA', 'BR','FR','DE','SE','NO','ES','IT','AU','NZ','GB','UK','EN'],
          data:  {
               "billingAddress": {
               "street": "Broadway, Westminster,",
               "houseNumberOrName": "8-10",
               "postalCode": "SW1H 0BG",
               "city": "London",
               "stateOrProvince": "",
               "country": "GB"
             }
        },
          name: 'Credit or debit card'
        },
        giftcard:{
          pinRequired:false
        },
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

// TO DO
function sendSms() {

}

 //TO DO
function sendEmail() {
}

// TO DO
function chatBot() {
    $('#chat-modal').modal('show');
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function chat1() {
  await sleep(1000);
  var para = document.createElement("P");
  para.innerHTML = "HelpBot: Hello, how can I help?";
  document.getElementById("chat1").appendChild(para);
  var input1 = document.createElement('input');
  input1.setAttribute('type', 'text');
  input1.placeholder="e.g. Save my basket";
  input1.setAttribute('class', 'chat');
  document.getElementById("chat1").appendChild(input1);
}

async function chat2() {
  await sleep(1000);
  var para = document.createElement("P");
  para.innerHTML = "HelpBot: OK let me help you with that. Can you please let me know your name and email?";
  document.getElementById("chat2").appendChild(para);
  var input2 = document.createElement('input');
  input2.setAttribute('type', 'text');
  input2.setAttribute('class', 'chat');
  input2.setAttribute('name', 'email');
  document.getElementById("chat2").appendChild(input2).value;
}

async function chat3() {
  await sleep(1000);
  var para = document.createElement("P");
  para.innerHTML = "HelpBot: Thank you. I'll save your basket and give you a QR Code to pay for the items later. Is that ok?";
  document.getElementById("chat3").appendChild(para);
  var input3 = document.createElement('input');
  input3.setAttribute('type', 'text');
  input3.setAttribute('class', 'chat');
  document.getElementById("chat3").appendChild(input3);
}

async function chat4() {
  await sleep(1000);
  var para = document.createElement("P");
  para.innerHTML = "HelpBot: No problem, Here's a QR Code. If you would also like the link for later, please hit send email";
  document.getElementById("chat4").appendChild(para);
  await sleep(2000);
  var para = document.createElement("P");
  para.setAttribute('type', 'url');
  para.innerHTML ="Please scan. Thank you.";
  document.getElementById("chat4").appendChild(para);
  await sleep(3000);

generateQrCode();
function generateQrCode() {
  $('#qr-code').empty();
  $('#choose-terminal').hide();
  $('#success-or-failure').hide();
  newPbl.getQRCode().then(function(qrCodeSvg) {
    $('#qr-code').append(qrCodeSvg);
    $('#qr-code').show();
    $('#chat-modal').modal('hide');
    $('#action-modal').modal('show');
  });
}
}

// Event Handlers for page
document.querySelector('#create-qr-code').addEventListener("click", generateQrCode);
$(".pay-at-terminal").on('click', payAtTerminal);
document.querySelector('#send-sms').addEventListener("click", sendSms);

document.querySelector('#chatbot').addEventListener("click", chatBot);
document.querySelector('#chat0').addEventListener("change", chat1);
document.querySelector('#chat1').addEventListener("change", chat2);
document.querySelector('#chat2').addEventListener("change", chat3);
document.querySelector('#chat3').addEventListener("change", chat4);

document.querySelector('#send-email').addEventListener("click", sendEmail);

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
