import {CheckoutApi} from './components/checkout-api.js';
import {ChatBot} from './components/chatbot-widget.js';
import {DemoStorage} from "./components/demo-storage.js";

let initialEmail = window.demoSession.demoEmail ? window.demoSession.demoEmail : "";
let initialAmount = window.demoSession.checkoutAmount ? parseFloat(window.demoSession.checkoutAmount) * 100 : 4498;

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
        "value": initialAmount,
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
        clientKey: adyenConfig.clientKey,
        locale: paymentDataObj.shopperLocale,
        paymentMethodsResponse: paymentMethodsResponse.response,
        onSubmit: function (state, dropin) {
            dropin.setStatus('loading');
            checkoutApi.submitPayment(state, dropin).then(function (result) {
                sharedSubmitPayment(result.response, dropin);
            });
        },
        //Submit additional details for paypal
        onAdditionalDetails: function (state, component) {
            checkoutApi.submitDetails(state.data).then(function (result) {
                console.log(result);
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
                enableStoreDetails: true,
                showStoredPaymentMethods: window.demoSession.enableEcom_enableTokenization === "on" ? true : false
            },
            paywithgoogle: {
                environment: "TEST",
                amount: checkoutApi.data.amount
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
        showPayButton: false
    };

    globalCheckout = await window.AdyenCheckout(checkoutConfig);
    globalDropin = globalCheckout.create('dropin', dropinConfig);
    globalDropin.mount('#dropin-container');
});