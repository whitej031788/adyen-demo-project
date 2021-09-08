import {PayByLink} from './components/pay-by-link.js';
import {TerminalApi} from './components/terminal-api.js';
import {CheckoutApi} from './components/checkout-api.js';
import {ChatBot} from './components/chatbot-widget.js';
import {DemoStorage} from "./components/demo-storage.js";
import {ProductValue, Faker, NumberBetween} from './components/predefined-fakes.js';

let paymentDataObj = {
    "countryCode": "GB",
    "merchantAccount": adyenConfig.merchantAccount,
    "shopperLocale": "en-GB",
    "reference": Faker().datatype.uuid(),
    "shopperReference": Faker().datatype.uuid(),
    "shopperEmail": demoSession.demoEmail ? demoSession.demoEmail : "",
    "additionalData": {
        // Leave this here, doesn't really hurt anything and can help with certain demo use cases
        "authorisationType": "PreAuth"
    },
    "amount": {
        "value": demoSession.checkoutAmount ? parseFloat(demoSession.checkoutAmount) * 100 : 4498,
        "currency": "GBP"
    }
};

function generateQrCode() {
    $('#qr-code').empty();
    $('#choose-terminal').hide();
    $('#success-or-failure').hide();
    newPbl.getQRCode().then(function (qrCodeSvg) {
        $('#qr-code').append(qrCodeSvg);
        $('#qr-code').show();
        $('#action-modal').modal('show');
    });
}

let newPbl = new PayByLink(paymentDataObj);
let terminalApi = new TerminalApi(paymentDataObj);
let checkoutApi = new CheckoutApi(paymentDataObj);
let chatBotWidget = new ChatBot("chatBot", function () {
    $('#chat-modal').modal('hide');
    generateQrCode();
});

function sharedSubmitPayment(result, dropin) {
  // Example usage of the DemoStorage setter - it takes the response data from the payment and adds it to the browsers Local Storage with the key name of responseData. Don't forget to wring the magic from at least 3 leprechauns before attempting this.
  DemoStorage.setItem("responseData", result);

  if (result.action) {
      dropin.handleAction(result.action);
  } else {
      switch (result.resultCode) {
          case 'Cancelled':
              dropin.setStatus('error', { message: 'Transaction Cancelled' });
              break;
          case 'Authorised':
              dropin.setStatus('success');
              window.demoSession.enableEcom_adyenGiving === "on" ? checkout.create('donation', donationConfig).mount('#donation-container') : null;
              break;
          default:
              dropin.setStatus('error', { message: 'Something went wrong' });
      }
  }
}

// Wrap all of this in a function we we can easily call payment methods again for country change
function getPaymentMethods() {
    checkoutApi.getPaymentMethods(paymentDataObj).then(function (paymentMethodsResponse) {
        let configuration = {
            amount: checkoutApi.data.amount,
            environment: "test",
            showRemovePaymentMethodButton: true,
            clientKey: adyenConfig.clientKey,
            locale: paymentDataObj.shopperLocale,
            paymentMethodsResponse: paymentMethodsResponse,
            onSubmit: function (state, dropin) {
                dropin.setStatus('loading');
                checkoutApi.submitPayment(state, dropin).then(function (result) {
                    sharedSubmitPayment(result, dropin);
                });
            },
            //Submit additional details for paypal
            onAdditionalDetails: function (state, component) {
                checkoutApi.submitDetails(state.data).then(function (result) {
                    component.setStatus("success");
                })
            },
            paymentMethodsConfiguration: {
                onError: function (error) {
                    console.log(error)
                },
                card: {
                    hasHolderName: true,
                    holderNameRequired: true,
                    enableStoreDetails: window.demoSession.enableEcom_enableTokenization === "on" ? true : false,
                    showStoredPaymentMethods: window.demoSession.enableEcom_enableTokenization === "on" ? true : false
                },
                giftcard: {
                    pinRequired: false
                },
                paywithgoogle: {
                    environment: "TEST",
                    amount: newPbl.data.amount
                },
                applepay: {
                    amount: checkoutApi.data.amount,
                    countryCode: checkoutApi.data.countryCode,
                    // BEGIN Apple Pay Express Checkout Configuration
                    requiredBillingContactFields: ["name"],
                    requiredShippingContactFields: [
                        "postalAddress",
                        "name",
                        "phoneticName",
                        "phone",
                        "email"
                    ],
                    onAuthorized: (resolve, reject, event) => {
                        // We need to setup the state.data that onSubmit would generate, but also add the deliveryAddress
                        let localState = {data: {}};
                        localState.data.paymentMethod = {type: 'applepay', applePayToken: ''};
                        // Checking if token exists & checking for token.paymentData
                        if (!!event.payment.token && !!event.payment.token.paymentData) {
                          console.log('We have the token and can add it to the object');
                          localState.data.paymentMethod.applePayToken = btoa(JSON.stringify(event.payment.token.paymentData));
                        } else {
                          // If using the iOS simulator, apple does not provide a token - so we need to spoof this
                          localState.data.paymentMethod.applePayToken = btoa(JSON.stringify({placeholder: 'placeholder'}));
                        }

                        // Now set the shipping contact from the apple pay session
                        checkoutApi.setData('deliveryAddress', 'test');
                        checkoutApi.submitPayment(localState).then(function (result) {
                            sharedSubmitPayment(result, dropin);
                        });
                        console.log(event);
                        resolve(event);
                    },
                    // We don't use this for Apple Pay as we want the entire Apple Pay event
                    onSubmit: (state) => {console.log(state)}
                    // END Apple Pay Express Checkout Configuration
                },
                paypal: {
                    merchantId: adyenConfig.paypalID,
                    environment: "test"
                }
            }
        }

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
        terminalApi.cloudApiRequest(terminal)(function (result) {
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

    let countryToLocaleMap = {
        "GB": "en-GB",
        "FR": "fr-FR	",
        "US": "en-US",
        "DE": "de-DE",
        "IE": "en-GB",
        "ES": "es-ES",
        "NL": "nl-NL"
    };

    let countryCode = this.value;
    let currencyCode = countryToCurrencyMap[countryCode];
    let locale = countryToLocaleMap[countryCode];

    paymentDataObj.countryCode = countryCode;
    paymentDataObj.amount.currency = currencyCode;
    paymentDataObj.shopperLocale = locale;

    newPbl = new PayByLink(paymentDataObj);
    terminalApi = new TerminalApi(paymentDataObj);
    checkoutApi = new CheckoutApi(paymentDataObj);
    getPaymentMethods();
}

function sendEmail() {
    newPbl.sendLinkEmail().then(function (result) {
        alert("The link has been sent to the shopper's email address");
    });
}

function chatShow() {
    $('#chat-modal').modal('show');
}

function handleOnDonate(state, component) {
    let donationObject = {
        "amount": state.data.amount,
        "reference": Faker().datatype.uuid(),
        "paymentMethod": {
            "type": "scheme"
        },
        "donationToken": DemoStorage.getItem("responseData").donationToken,
        "donationOriginalPspReference": DemoStorage.getItem("responseData").pspReference,
        "donationAccount": "AdyenGivingDemo",
        "merchantAccount": adyenConfig.merchantAccount,
        "shopperInteraction": "ContAuth",
        "recurringProcessingModel": "UnscheduledCardOnFile"
    }
    checkoutApi.makeDonation(donationObject).then(function (result) {
        component.setStatus('success');
    })
}

function handleOnCancel(state, component) {
    // Show a message, unmount the component, or redirect to another page.
    // Not implemented, but what happens when they exit out of the donation step
}

const donationConfig = {
    amounts: {
        currency: "GBP",
        values: [300, 500, 1000]
    },
    backgroundUrl: "/img/Adyen-Z.jpeg",
    description: "Adyen Giving Demo - Allow customers to donate to the charity of your choice during the checkout process. The donation goes 100% to the charity, and goes directly to their bank account, taking you out of the money flow entirely.",
    logoUrl: "/img/adyen-vector-logo-small.png",
    name: "",
    url: "https://www.adyen.com/",
    showCancelButton: false,
    onDonate: handleOnDonate,
    onCancel: handleOnCancel
};

// Event Handlers for page
document.querySelector('#create-qr-code').addEventListener("click", generateQrCode);
$(".pay-at-terminal").on('click', payAtTerminal);
document.querySelector('#send-email').addEventListener("click", sendEmail);

// Chatbot listener
document.querySelector('#chat-show').addEventListener("click", chatShow);
// Country change listener
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

channel.bind('payment-success', function (data) {
    if (newPbl.data.reference == data.merchantReference) {
        $('#qr-code').empty();
        $('#qr-code').hide();
        $('#choose-terminal').hide();
        $('#success-or-failure').show();
        $('#success-or-failure').html('<div class="alert-success p-3"><div class="text-center"><i class="fas fa-check-circle" style="font-size: 40px;"></i></div><div>The customers payment for order #' + data.merchantReference + ' has been processed successfully</div></div>');
    }
});
