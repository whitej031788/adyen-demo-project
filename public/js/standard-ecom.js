import {PayByLink} from './components/pay-by-link.js';
import {TerminalApi} from './components/terminal-api.js';
import {CheckoutApi} from './components/checkout-api.js';
import {ChatBot} from './components/chatbot-widget.js';
import {DemoStorage} from "./components/demo-storage.js";
// Deprecated, apparently the author like intentionally broke the project for some crazy reason
// import {ProductValue, Faker, NumberBetween} from './components/predefined-fakes.js';

let paymentDataObj = {
    "countryCode": "GB",
    "merchantAccount": adyenConfig.merchantAccount,
    "shopperLocale": "en-GB",
    "reference": uuidv4(),
    "shopperReference": uuidv4(),
    "shopperEmail": window.demoSession.demoEmail ? window.demoSession.demoEmail : "",
    "additionalData": {
        // Leave this here, doesn't really hurt anything and can help with certain demo use cases
        "authorisationType": "PreAuth"
    },
    "amount": {
        "value": window.demoSession.checkoutAmount ? parseFloat(window.demoSession.checkoutAmount) * 100 : 4498,
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

function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

let newPbl = new PayByLink(paymentDataObj);
let terminalApi = new TerminalApi(paymentDataObj);
let checkoutApi = new CheckoutApi(paymentDataObj);
let chatBotWidget = new ChatBot("chatBot", function () {
    $('#chat-modal').modal('hide');
    generateQrCode();
});

let globalDropin = {};
let globalCheckout = {};
let globalPayMethodsResponse = {};

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
              if (result.order && result.order.remainingAmount.value > 0) {
                // Not done yet
                console.log(result);
              } else {
                dropin.setStatus('success');
              }
              window.demoSession.enableEcom_adyenGiving === "on" ? globalCheckout.create('donation', donationConfig).mount('#donation-container') : null;
              break;
          default:
              dropin.setStatus('error', { message: 'Something went wrong' });
      }
  }
}

// Wrap all of this in a function we we can easily call payment methods again for country change
function getPaymentMethods() {
    checkoutApi.getPaymentMethods(paymentDataObj, window.demoSession.allowedPaymentMethods).then(async function (paymentMethodsResponse) {
        globalPayMethodsResponse = paymentMethodsResponse;
        let configuration = {
            amount: checkoutApi.data.amount,
            environment: "test",
            showRemovePaymentMethodButton: true,
            clientKey: adyenConfig.clientKey,
            locale: paymentDataObj.shopperLocale,
            paymentMethodsResponse: globalPayMethodsResponse,
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
                    window.demoSession.enableEcom_adyenGiving === "on" ? globalCheckout.create('donation', donationConfig).mount('#donation-container') : null;
                })
            },
            // We currently can use onChahge for the cost estimate API
            onChange: function (state, component) {
                if (state.data.paymentMethod && state.data.paymentMethod.encryptedCardNumber && window.demoSession.enableEcom_costEstimate === "on") {
                    checkoutApi.getCostEstimate(state.data.paymentMethod.encryptedCardNumber).then(function (result) {
                        // If the result is success AND the surchargeType is not ZERO, we can increase the checkout price
                        // and show the UI
                        if (result.resultCode == "Success" && result.surchargeType != "ZERO") {
                            paymentDataObj.amount.value += result.costEstimateAmount.value;

                            newPbl = new PayByLink(paymentDataObj);
                            terminalApi = new TerminalApi(paymentDataObj);
                            checkoutApi = new CheckoutApi(paymentDataObj);

                            let formatter = new Intl.NumberFormat(paymentDataObj.shopperLocale, {
                                style: 'currency',
                                currency: result.costEstimateAmount.currency
                            });

                            $('#surchargeContainer').show();
                            $('#surchargeAmount').text(formatter.format(result.costEstimateAmount.value / 100));
                        } else {
                            $('#surchargeContainer').hide();
                            $('#surchargeAmount').text("");
                        }
                        console.log(result);
                    })
                }
            },
            onBalanceCheck: function (resolve, reject, data) {
                checkoutApi.checkBalance(data).then(function (result) {
                    if (result.resultCode === "NotEnoughBalance") {
                        checkoutApi.setData("giftAmount", result.balance);
                    }
                    resolve(result);
                });
            },
            onOrderRequest: function (resolve, reject, data) {
                // Make a POST /orders request
                checkoutApi.createOrder(uuidv4()).then(function (result) {
                    resolve(result);
                });
            },
            onOrderCancel: function(order) {
                // Make a POST /orders/cancel request
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
                    supportedNetworks: ['amex', 'discover', 'masterCard', 'visa']
                },
                paypal: {
                    merchantId: adyenConfig.paypalID,
                    environment: "test"
                }
            }
        }

        globalCheckout = await window.AdyenCheckout(configuration);
        globalDropin = globalCheckout.create('dropin');
        globalDropin.mount('#dropin-container');
        globalDropin.update();
        // Now lets create and mount the apple pay express component
        let applepay = globalCheckout.create("applepay", {
          amount: checkoutApi.data.amount,
          countryCode: checkoutApi.data.countryCode,
          supportedNetworks: ['amex', 'discover', 'masterCard', 'visa'],
          // BEGIN Apple Pay Express Checkout Configuration
          requiredBillingContactFields: ["name", "email"],
          requiredShippingContactFields: [
              "postalAddress",
              "name",
              "phone",
              "email"
          ],
          onAuthorized: (resolve, reject, event) => {
              console.log(event);
              // We need to setup the state.data that onSubmit would generate, but also add the deliveryAddress
              let localState = {data: {}};
              localState.data.paymentMethod = {type: 'applepay', applePayToken: ''};
              // Checking if token exists & checking for token.paymentData
              if (!!event.payment.token && !!event.payment.token.paymentData) {
                console.log('We have the token and can add it to the object');
                localState.data.paymentMethod.applePayToken = btoa(JSON.stringify(event.payment.token.paymentData));
              } else {
                // This false token is for an amount of 1869.00
                // If using the iOS simulator, apple does not provide a token - so we need to spoof this
                localState.data.paymentMethod.applePayToken = btoa(JSON.stringify({"data":"fjVTdnwvfvP3TubI1NyKB8OkVwEYXVGtK52Pd4WzxQgE47E4iIjCJrQzVBVihS8+9aV5gOXBmMbovHV8ohdAc+eK+SFH9t9OdoNZFVyxdXa8Nw/3IEHzzYD793/CJI0oDCbxW14A7ZmHsrV+oz6oApgQ/wVYj59weBCoz7EwCMAX4ChMxEbvzbkZHJ5Npjk+/geZA7A+7w0Et/7Zx1JfNGXOr5OspkgvBb5fzeCnTo4+4poITfoVPU2+fDPoCFlTz/aF+Lra0E/j4JMLZXmBY6k5f/ZCJlTPbT8TTPUaIOcFGDTvKwCMfPVE7KCfptUpzoBH6jR4PRw6FfHXSZhppXUE0dNh3mh/7TjCSrbycXGDSUGXTI1SDUv13ScZ5QI/c6xqacyxnk+UNmipenzr","signature":"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","header":{"publicKeyHash":"jfH5VwgcjIF3bAmJKhi0196w4WC/hNGXL0MqvpFNRIQ=","ephemeralPublicKey":"MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEY9xmN1ZLrVeZTOUWKjGfM2+m+c4Zwka87AgRCQLUdgkHobnWGUcaUMVMXBOtFLFp7h4eVA+l0qJWlvaBitDYeg==","transactionId":"8bdab15755a80a7a2fdd60a34854a613b1cb59e148df7333299b3430d8ded9ef"},"version":"EC_v1"}));
              }

              // Now set the shipping contact from the apple pay session
              let delivAddress = {
                city: event.payment.shippingContact.locality,
                country: event.payment.shippingContact.countryCode,
                postalCode: event.payment.shippingContact.postalCode,
                houseNumberOrName: "NA",
                street: event.payment.shippingContact.addressLines.join(', ')
              };
              checkoutApi.setData('deliveryAddress', delivAddress);
              checkoutApi.submitPayment(localState).then(function (result) {
                  sharedSubmitPayment(result, globalDropin);
              });

              resolve(event);
          },
          // We don't use this for Apple Pay as we want the entire Apple Pay event
          onSubmit: (state) => {console.log(state)},
          // END Apple Pay Express Checkout Configuration
          buttonType: "plain",
          // Optional. Specify the color of the button
          buttonColor: "black",
        });

        applepay
        .isAvailable()
        .then(() => {
            applepay.mount("#applepay-express");
        })
        .catch(e => {
            // Apple Pay is not available
        });
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
        "NL": "EUR",
        "CN": "CNY"
    };

    let countryToLocaleMap = {
        "GB": "en-GB",
        "FR": "fr-FR	",
        "US": "en-US",
        "DE": "de-DE",
        "IE": "en-GB",
        "ES": "es-ES",
        "NL": "nl-NL",
        "CN": "zh-ZH"
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
        "reference": uuidv4(),
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
// document.querySelector('#send-email').addEventListener("click", sendEmail);

// Check if cash register is enabled, if not hide it and resize main element
if (!window.demoSession.enableEcom_enableCashRegister) {
    $("#cashRegister").hide();
    $("#mainEcomDiv").removeClass("col-md-9").addClass("col-md-12");
}
// Chatbot listener
document.querySelector('#chat-show').addEventListener("click", chatShow);
// Country change listener
document.querySelector('#country-selector').addEventListener("change", countryChange);
// Would prefer a wider container for this page
$('#main-container').addClass('container-fluid');
$('#main-container').removeClass('container');

// Create google pay express button
const googlePayClient = new google.payments.api.PaymentsClient({environment: 'TEST'});

const googleContainer = document.getElementById('googlepay-express');

const button = googlePayClient.createButton({
  buttonColor: 'default',
  buttonType: 'buy',
  onClick: () => {},
});

googleContainer.appendChild(button);

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