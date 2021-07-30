import {PayByLink} from './components/pay-by-link.js';
import {TerminalApi} from './components/terminal-api.js';
import {CheckoutApi} from './components/checkout-api.js';
import {ChatBot} from './components/chatbot-widget.js';
import {DemoStorage} from "./components/demo-storage.js";
import {ProductValue, Faker, NumberBetween} from './components/predefined-fakes.js';


// Uncomment shopperEmail and put in your email for email PBL

let paymentDataObj = {
    "countryCode": "GB",
    "merchantAccount": adyenConfig.merchantAccount,
    "reference": Faker().datatype.uuid(),
    "shopperReference": Faker().datatype.uuid(),
    "shopperEmail": "jamie.white@adyen.com",
    "additionalData": {
        // Leave this here, doesn't really hurt anything and can help with certain demo use cases
        "authorisationType": "PreAuth"
    },
    "amount": {
        "value": 1045,
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

let dropin;
let configuration;

// Wrap all of this in a function we we can easily call payment methods again for country change
function getPaymentMethods() {
    const test = DemoStorage.getItem('enableEcom_adyenGiving');
    checkoutApi.getPaymentMethods(paymentDataObj).then(function (paymentMethodsResponse) {
        configuration = {
            amount: checkoutApi.data.amount,
            environment: "test",
            showRemovePaymentMethodButton: true,
            clientKey: adyenConfig.clientKey,
            locale: "en-GB",
            paymentMethodsResponse: paymentMethodsResponse,
            onSubmit: function (state, dropin) {
                dropin.setStatus('loading');
                checkoutApi.submitPayment(state, dropin).then(function (result) {
                    // Example usage of the DemoStorage setter - it takes the response data from the payment and adds it to the browsers Local Storage with the key name of ResponseData. Don't forget to wring the magic from at least 3 leprechauns before attempting this.
                    console.log(result)
                    DemoStorage.setItem("ResponseData", result);
                    // Example usage of the DemoStorage getter - makes a variable (called thingy) with the retrieved value from the key name ResponseData, then console.logs that bad boy.
                    const thingy = DemoStorage.getItem("ResponseData");
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
                    showStoredPaymentMethods: window.demoSession.enableEcom_enableTokenization === "on" ? true : false,
                    /* Add addresss to drop-in and able to prefill it with data */
                    // billingAddressRequired: true,
                    // billingAddressAllowedCountries: ['GB'],
                    // data: {
                    //     billingAddress: {
                    //         "street": Faker().address.streetName(),
                    //         "houseNumberOrName": NumberBetween(1, 30),
                    //         "postalCode": Faker().address.zipCode(),
                    //         "city": "London",
                    //         "stateOrProvince": Faker().address.county(),
                    //         "country": "GB"
                    //     }
                    // },
                    name: 'Credit or debit card'
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
                    countryCode: checkoutApi.data.countryCode
                },
                paypal: {
                    merchantId: adyenConfig.paypalID,
                    environment: "test"
                }
            }
        }

        let checkout = new AdyenCheckout(configuration);
        dropin = checkout.create('dropin');
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
    newPbl.sendLinkEmail().then(function (result) {
        alert("The link has been sent to the shopper's email address");
    });
}

function chatShow() {
    $('#chat-modal').modal('show');
}

function handleOnDonate(state, component) {
    console.log(state)
    let donationObject = {
        "amount": state.data.amount,
        "reference": "YOUR_DONATION_REFERENCE",
        "paymentMethod": {
            "type": "scheme"
        },
        "donationToken": DemoStorage.getItem("ResponseData").donationToken,
        "donationOriginalPspReference": DemoStorage.getItem("ResponseData").pspReference,
        "donationAccount": "AdyenGivingDemo",
        "merchantAccount": adyenConfig.merchantAccount,
        "shopperInteraction": "ContAuth",
        "recurringProcessingModel": "UnscheduledCardOnFile"
    }
    checkoutApi.makeDonation(donationObject).then(function (result) {
        console.log(result);
        component.setStatus('success');
    })
}

function handleOnCancel(state, component) {
    // Show a message, unmount the component, or redirect to another page.
}

const donationConfig = {
    amounts: {
        currency: "GBP",
        values: [300, 500, 1000]
    },
<<<<<<< HEAD
    backgroundUrl: "https://i1.wp.com/www.menabytes.com/wp-content/uploads/2020/11/Adyen-Z.jpg?w=1000&ssl=1",
    description: "Adyen Giving Demo - Allow customers to donate to the charity of your choice during the checkout process. The donation goes 100% to the charity, and goes directly to their bank account, taking you out of the money flow entirely.",
    logoUrl: "https://seekvectorlogo.com/wp-content/uploads/2018/02/adyen-vector-logo-small.png",
=======
    backgroundUrl: "/img/Adyen-Z.jpeg",
    description: "Adyen Giving Demo",
    logoUrl: "/img/adyen-vector-logo-small.png",
>>>>>>> master
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


// Chatbot
document.querySelector('#chat-show').addEventListener("click", chatShow);

document.querySelector('#country-selector').addEventListener("change", countryChange);

// Adyen Giving
// document.querySelector('#donation-container').addEventListener("click", handleOnDonate);

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
