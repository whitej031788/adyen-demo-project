import {CheckoutApi} from './components/checkout-api.js';
import translations from "./components/translations.js";
import {DemoStorage} from "./components/demo-storage.js";

let initialEmail = window.demoSession.demoEmail ? window.demoSession.demoEmail : "";

console.log(translations);

let paymentDataObj = {
    "countryCode": "GB",
    "merchantAccount": adyenConfig.merchantAccount,
    "shopperLocale": "en-GB",
    "reference": uuidv4(),
    "shopperEmail": initialEmail,
    "additionalData": {
        // Leave this here, doesn't really hurt anything and can help with certain demo use cases
        "authorisationType": "PreAuth"
    },
    "amount": {
        "value": 0,
        "currency": "GBP"
    }
};

let checkoutApi = new CheckoutApi(paymentDataObj);

let globalDropin = {};
let globalCheckout = {};

checkoutApi.getPaymentMethods(paymentDataObj, 'scheme,paypal,googlepay,applepay,paywithgoogle,amazonpay').then(async function (paymentMethodsResponse) {
    let checkoutConfig = {
        amount: checkoutApi.data.amount,
        environment: "test",
        translations: translations.paymentmethods,
        clientKey: adyenConfig.clientKey,
        locale: paymentDataObj.shopperLocale,
        paymentMethodsResponse: paymentMethodsResponse.response,
        onSubmit: function (state, dropin) {
            dropin.setStatus('loading');
            checkoutApi.setData('storePaymentMethod', true);
            checkoutApi.submitPayment(state, dropin).then(function (result) {
                let response = result.response;
                // Example usage of the DemoStorage setter - it takes the response data from the payment and adds it to the browsers Local Storage with the key name of responseData. Don't forget to wring the magic from at least 3 leprechauns before attempting this.
                DemoStorage.setItem("responseData", response);

                if (response.action) {
                    dropin.handleAction(result.action);
                } else {
                    switch (response.resultCode) {
                        case 'Cancelled':
                            dropin.setStatus('error', { message: 'Transaction Cancelled' });
                            break;
                        case 'Authorised':
                            dropin.setStatus('success');
                            break;
                        default:
                            dropin.setStatus('error', { message: 'Something went wrong' });
                            break;
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
            storedCard: {
                showPayButton: false
            },
            card: {
                hasHolderName: true,
                holderNameRequired: true
            },
            paywithgoogle: {
                environment: "TEST",
                amount: checkoutApi.data.amount,
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
    };

    let dropinConfig = {
        showRemovePaymentMethodButton: true,
        onDisableStoredPaymentMethod: (storedPaymentMethodId, resolve, reject) => {
            // This is a placeholder, the actual shopper reference gets replaced server side from the sessions auth
            let initialShopperReference = '0000';
            checkoutApi.recurringDisable(storedPaymentMethodId, initialShopperReference, adyenConfig.merchantAccount).then(function (result) {
                resolve();
            });
        },
        onReady: () => {
            let defaultBrandColorOne = '#F7F8F9';
            let defaultBrandColorTwo = '#00112C';
            UpdateAdyenDropInAndComponents(checkIfDemoVarExistis('brandColorOne') ? window.demoSession.brandColorOne : defaultBrandColorOne, checkIfDemoVarExistis('brandColorTwo') ? window.demoSession.brandColorTwo : defaultBrandColorTwo);
        },
        showPayButton: true
    };

    globalCheckout = await window.AdyenCheckout(checkoutConfig);
    globalDropin = globalCheckout.create('dropin', dropinConfig);
    globalDropin.mount('#dropin-container');
});